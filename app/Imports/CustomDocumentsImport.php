<?php

namespace App\Imports;

use App\Models\Tenant\Item;
use App\Models\Tenant\Series;
use App\Models\Tenant\Document;
use App\Models\Tenant\Person;
use App\Http\Resources\Tenant\PersonResource;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Modules\Import\Models\ImportDocument;
use Modules\Services\Data\ServiceData;

class CustomDocumentsImport implements ToCollection
{
    use Importable;

    protected $data;

    public function collection(Collection $rows)
    {

        
            $total = count($rows);
            $message = "El archivo fue cargado satisfactoriamente";
            $success = true;
            $registered = 0;
            unset($rows[0]);
            $i = 1;
            // dd(count($rows));
            $quantity_rows = count($rows);
            
            if($quantity_rows){
                $import_document = ImportDocument::create(
                    ['user_id'=> auth()->user()->id]
                );
                $import_document->filename = "{$import_document->id}-{$import_document->user_id}-".date('YmdHis');
                $import_document->save();
            }

            for($i; $i <= $quantity_rows; $i++)
            { 

                $row = $rows[$i];
                $row_items = [];


                //unidad de medida 
                $unit_type = 'NIU';
                $unit_price = $row[32];
                $unit_value = round($unit_price/1.18,2);
                $quantity = 1;
                $total_igv_item = $unit_price - $unit_value;
                $total_valor_item = $unit_value * $quantity;
                $total_item = $unit_price * $quantity;
                $order_number = (string)$row[6];

                $row_items [] = [
                    "codigo_interno" => $row[3],
                    "descripcion" => rtrim($row[35]),
                    "codigo_producto_sunat" => "",
                    "unidad_de_medida" => $unit_type,
                    "cantidad" => $quantity, //todo
                    "valor_unitario" => $unit_value,
                    "codigo_tipo_precio" => "01",
                    "precio_unitario" => $unit_price,
                    "codigo_tipo_afectacion_igv" => "10",
                    "total_base_igv" => $unit_value * $quantity, //todo
                    "porcentaje_igv" => "18",
                    "total_igv" => $total_igv_item,
                    "total_impuestos" => $total_igv_item,
                    "total_valor_item" => $total_valor_item,
                    "total_item" => $total_item,
                    "order" => self::order($row)
                ];
 

                for ($j=$i+1; $j <= $quantity_rows; $j++) { 
               
                    // dd((string)$row[6], (string)$rows[$j][6], (string)$rows[$j+1][6]);
                    
                    if((string)$row[6] == (string)$rows[$j][6]){
                        
                        $row_temp = $rows[$j];
                        
                        $unit_price = $row_temp[32];
                        $unit_value = round($unit_price/1.18,2);
                        $quantity = 1;
                        $total_igv_item = $unit_price - $unit_value;
                        $total_valor_item = $unit_value * $quantity;
                        $total_item = $unit_price * $quantity;

                        $row_items [] = [
                            "codigo_interno" => $row_temp[3],
                            "descripcion" => rtrim($row_temp[35]),
                            "codigo_producto_sunat" => "",
                            "unidad_de_medida" => $unit_type,
                            "cantidad" => $quantity, //todo
                            "valor_unitario" => $unit_value,
                            "codigo_tipo_precio" => "01",
                            "precio_unitario" => $unit_price,
                            "codigo_tipo_afectacion_igv" => "10",
                            "total_base_igv" => $unit_value * $quantity, //todo
                            "porcentaje_igv" => "18",
                            "total_igv" => $total_igv_item,
                            "total_impuestos" => $total_igv_item,
                            "total_valor_item" => $total_valor_item,
                            "total_item" => $total_item,
                            "order" => self::order($row_temp)
                        ];
 
                        unset($rows[$j]);
                        $i = $j;
                        // dd($j);
                    }
                }
                
                
                $document_type_operation = '0101';
                $correlativo = "#";

                if($row[9] == 'Yes'){
                    $document_type = '01';
                } elseif($row[9] == 'No'){
                    $document_type = '03';
                } 

                $establishment_id = auth()->user()->establishment_id;
                $record_serie = Series::where([['document_type_id',$document_type],['establishment_id',$establishment_id]])->first();

                if($record_serie){
                    $serie = $record_serie->number;
                }else{
                    if($row[9] == 'Yes'){
                        $message = "Se procesaron {$registered} documentos del total: No tiene serie registrada para factura";
                    } elseif($row[9] == 'No'){
                        $message = "Se procesaron {$registered} documentos del total: No tiene serie registrada para boleta";
                    } 
                    $success = false;
                    return $this->data = compact('total', 'registered','message','success');            
                }
                 

                $create_date = $row[4];
                $date_create = Carbon::parse($create_date)->format('Y-m-d');
                $time_create = Carbon::parse($create_date)->format('H:i:s');

                $currency = $row[8];

                //cliente
                $co_number = rtrim($row[11]);
                if ($co_number > 0) {
                    if (strlen($co_number) == 11) {
                        $client_document_type = '6';
                        $company_number = $co_number;
                    } elseif (strlen($co_number) == 8) {
                        $client_document_type = '1';
                        $company_number = $co_number;
                    }
                } 

                if($document_type == '01'){
                    if(strlen($co_number) !== 11){
                        $success = false;
                        $message = "Se procesaron {$registered} documentos del total: No puede generar una factura con DNI";
                        return $this->data = compact('total', 'registered','message','success');
                    }
                    // $this->data = compact('total', 'registered');
                }

                $company_address = $row[13];
                $company_name = $row[10];
 

                
                if($document_type == '01'){
                    
                    $search_customer = ServiceData::service('ruc', $company_number);

                    if($search_customer['success']){

                        $datos_del_cliente_o_receptor = [
                            "codigo_tipo_documento_identidad" => $client_document_type,
                            "numero_documento" => $search_customer['data']['ruc'],
                            "apellidos_y_nombres_o_razon_social" => $search_customer['data']['nombre_o_razon_social'],
                            "codigo_pais" => 'PE',
                            "ubigeo" => (count($search_customer['data']['ubigeo'])  == 3) ? $search_customer['data']['ubigeo'][2] : null,
                            "direccion" => $search_customer['data']['direccion'],
                            "correo_electronico" => "",
                            "telefono" => ""
                        ];

                    }else{

                        $datos_del_cliente_o_receptor = [
                            "codigo_tipo_documento_identidad" => $client_document_type,
                            "numero_documento" => $company_number,
                            "apellidos_y_nombres_o_razon_social" => rtrim($company_name),
                            "codigo_pais" => "PE",
                            "ubigeo" => null,
                            "direccion" => rtrim($company_address),
                            "correo_electronico" => "",
                            "telefono" => ""
                        ];
                    }
                    // dd($search_customer);
                }else{

                    $search_customer = ServiceData::service('dni', $company_number);
                    
                    if($search_customer['success']){

                        $datos_del_cliente_o_receptor = [
                            "codigo_tipo_documento_identidad" => $client_document_type,
                            "numero_documento" => $search_customer['data']['numero'],
                            "apellidos_y_nombres_o_razon_social" => $search_customer['data']['nombre_completo'],
                            "codigo_pais" => 'PE',
                            "ubigeo" => null,
                            "direccion" => "",
                            "correo_electronico" => "",
                            "telefono" => ""
                        ];

                    }else{

                        $datos_del_cliente_o_receptor = [
                            "codigo_tipo_documento_identidad" => $client_document_type,
                            "numero_documento" => $company_number,
                            "apellidos_y_nombres_o_razon_social" => rtrim($company_name),
                            "codigo_pais" => "PE",
                            "ubigeo" => null,
                            "direccion" => "",
                            "correo_electronico" => "",
                            "telefono" => ""
                        ];
                    }
                    // dd($search_customer);                    

                }

                

                //totales
                $acum_total_item = 0;
                $acum_total_envio = 0;

                foreach ($row_items as $item) {
                    
                    // dd($item);
                    $acum_total_item += $item['total_item'];
                    $acum_total_envio += $item['order']['shipping_fee'];
                     
                }

                //envio
                if ($acum_total_envio) {

                    $item_send = [
                        "codigo_interno" => "ENVIO",
                        "descripcion" => "ENVÍO",
                        "codigo_producto_sunat" => "",
                        "unidad_de_medida" => $unit_type,
                        "cantidad" => $quantity,
                        "valor_unitario" => round($acum_total_envio/1.18,2),
                        "codigo_tipo_precio" => "01",
                        "precio_unitario" => $acum_total_envio,
                        "codigo_tipo_afectacion_igv" => "10",
                        "total_base_igv" => round($acum_total_envio/1.18,2),
                        "porcentaje_igv" => "18",
                        "total_igv" => $acum_total_envio - round($acum_total_envio/1.18,2),
                        "total_impuestos" => $acum_total_envio - round($acum_total_envio/1.18,2),
                        "total_valor_item" => round($acum_total_envio/1.18,2),
                        "total_item" => $acum_total_envio,
                    ];

                    array_push($row_items, $item_send);
                }

                $mtototal = $acum_total_item + $acum_total_envio;
                $mtosubtotal = round($mtototal/1.18,2);
                $mtoimpuesto = $mtototal - $mtosubtotal;

                 
                
                $json = array(
                    "serie_documento" => $serie,
                    "numero_documento" => $correlativo,
                    "fecha_de_emision" => $date_create,
                    "hora_de_emision" => $time_create,
                    "codigo_tipo_operacion" => $document_type_operation,
                    "codigo_tipo_documento" => $document_type,
                    "codigo_tipo_moneda" => $currency,
                    "fecha_de_vencimiento" => $date_create,
                    "numero_orden" => $order_number,
                    "id_importacion_documento" => $import_document->id,
                    "totales" => [
                        "total_exportacion" => 0.00,
                        "total_operaciones_gravadas" => $mtosubtotal,
                        "total_operaciones_inafectas" => 0.00,
                        "total_operaciones_exoneradas" => 0.00,
                        "total_operaciones_gratuitas" => 0.00,
                        "total_igv" => $mtoimpuesto,
                        "total_impuestos" => $mtoimpuesto,
                        "total_valor" => $mtosubtotal,
                        "total_venta" => $mtototal
                    ],
                    "datos_del_emisor" => [
                        "codigo_del_domicilio_fiscal" => "0000"
                    ],
                    "datos_del_cliente_o_receptor" => $datos_del_cliente_o_receptor,
                    "items" => $row_items 
                );

                 

                $url = url('/api/documents');
                $token = auth()->user()->api_token;

                // dd($json);

                try {

                    $client = new \GuzzleHttp\Client();

                    $res = $client->post($url, [
                        'headers' => [
                            'Content-Type' => 'Application/json',
                            'Authorization' => 'Bearer '.$token
                        ],
                        'json' => $json
                    ]);

                    $response = json_decode($res->getBody()->getContents(), true);
// dd($res);
                    // Document::where('external_id', $response['data']['external_id'])->update(['import_document_id' => $import_document->id]);
                    // dd($response['data']['external_id']);

                } catch (Exception $e) {
                    throw new Exception("Error Processing Request", 1);
                    
                }

                $registered += 1;
            }

            
            $this->data = compact('total', 'registered','message','success');

    }

    private static function order($row){

        return  [
            "order_item_id" => $row[0],
            "linio_id" => $row[1],
            "seller_sku" => $row[2],
            "linio_sku" => $row[3],
            "created_at" => $row[4],
            "updated_at" => $row[5],
            "order_number" => $row[6],
            "order_source" => $row[7],
            "order_currency" => $row[8],
            "invoice_required" => $row[9],
            "customer_name" => $row[10],
            "national_registration_number" => $row[11],
            "shipping_name" => $row[12],
            "shipping_address" => $row[13],
            "shipping_address2" => $row[14],
            "shipping_city" => $row[18],
            "shipping_country" => $row[20],
            "billing_name" => $row[21],
            "billing_address" => $row[22], 
            "payment_method" => $row[30],
            "paid_price" => $row[31],
            "unit_price" => $row[32],
            "shipping_fee" => $row[33],
            "item_name" => $row[35], 
            "shipping_provider" => $row[38],
            "shipping_type_name" => $row[39],
            "shipping_provider_type" => $row[40],
            "cd_traking_code" => $row[41],
            "traking_code" => $row[42],
            "traking_url" => $row[43],
            "promised_shipping_time" => $row[44],
            "premium" => $row[45],
            "status" => $row[46],
            "reason" => $row[47]
        ];

    }

    public function getData()
    {
        return $this->data;
    }
}
