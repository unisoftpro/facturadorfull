<?php

namespace Modules\Import\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Configuration;
use Modules\Import\Models\ImportDocument;
use Modules\Import\Http\Resources\ImportDocumentCollection;
use App\Imports\CustomDocumentsImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Exception\Exception;
use App\Models\Tenant\Company;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Template;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;


class ImportController extends Controller
{
    
    use StorageDocument;
     
    public function index()
    {
        if(!Configuration::first()->import_documents)
            return redirect()->route('tenant.documents.index');

        return view('import::documents.index');
    }
 
    public function columns()
    {
        return [
            'id' => 'Identificador',
        ];
    }

    public function records(Request $request)
    {
        $records = ImportDocument::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ImportDocumentCollection($records->paginate(config('tenant.items_per_page')));
    }

    
    public function destroy($id)
    {

        $import_document = ImportDocument::findOrFail($id); 
        $documents = $import_document->documents->where('state_type_id','!=','01')->count();

        if($documents > 0){

            return [
                'success' => false,
                'message' => 'No puede eliminar, al menos un documento ha sido enviado'
            ];

        }else{
            $import_document->documents()->delete();
        }

        $import_document->delete();

        return [
            'success' => true,
            'message' => 'Importación de documentos eliminada con éxito'
        ];
    }

    
    public function import(Request $request)
    {
 
        if ($request->hasFile('file')) {
            try {
                $import = new CustomDocumentsImport();
                $import->import($request->file('file'), null, Excel::XLSX);
                $data = $import->getData();
                return [
                    'success' => true,
                    'message' =>  __('app.actions.upload.success'),
                    'data' => $data
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' =>  $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

 
    public function toPrint($id, $format) {

        $import_document = ImportDocument::find($id);
        if (!$import_document) throw new Exception("El código {$external_id} es inválido, no se encontró el registro asociado");
        $documents = $import_document->documents;
        
        $this->reloadPDF($documents, $format, $import_document->filename);
        
        $temp = tempnam(sys_get_temp_dir(), 'import_documents');
        // dd($documents);
        
        file_put_contents($temp, $this->getStorage($import_document->filename,'import_documents'));
        
        return response()->file($temp);

    }
    
    private function reloadPDF($documents, $format, $filename) {
        $this->createPdf($documents, $format, $filename);
    }
    
    public function createPdf($documents = [], $format_pdf = null, $filename = null) {
        ini_set("pcre.backtrack_limit", "5000000");

        $template = new Template();
        $pdf = new Mpdf();
 
        $company = Company::active();
        $filename = $filename;
        $base_pdf_template = config('tenant.pdf_template');
        
        foreach ($documents as $document) {    
             
            $html = $template->pdf($base_pdf_template, "import_document", $company, $document, $format_pdf);
            
            if (($format_pdf === 'ticket') OR ($format_pdf === 'ticket_58')) {

                $width = ($format_pdf === 'ticket_58') ? 56 : 78 ;
                if(config('tenant.enabled_template_ticket_80')) $width = 76;
                
                $company_name      = (strlen($company->name) / 20) * 10;
                $company_address   = (strlen($document->establishment->address) / 30) * 10;
                $company_number    = $document->establishment->telephone != '' ? '10' : '0';
                $customer_name     = strlen($document->customer->name) > '25' ? '10' : '0';
                $customer_address  = (strlen($document->customer->address) / 200) * 10;
                $customer_department_id  = ($document->customer->department_id == 16) ? 20:0; 
                $p_order           = $document->purchase_order != '' ? '10' : '0';
    
                $total_exportation = $document->total_exportation != '' ? '10' : '0';
                $total_free        = $document->total_free != '' ? '10' : '0';
                $total_unaffected  = $document->total_unaffected != '' ? '10' : '0';
                $total_exonerated  = $document->total_exonerated != '' ? '10' : '0';
                $total_taxed       = $document->total_taxed != '' ? '10' : '0';
                $perception       = $document->perception != '' ? '10' : '0';
    
                $total_plastic_bag_taxes       = $document->total_plastic_bag_taxes != '' ? '10' : '0';
                $quantity_rows     = count($document->items);
    
                $extra_by_item_description = 0;
                $discount_global = 0;
                foreach ($document->items as $it) {
                    if(strlen($it->item->description)>100){
                        $extra_by_item_description +=24;
                    }
                    if ($it->discounts) {
                        $discount_global = $discount_global + 1;
                    }
                }
                $legends = $document->legends != '' ? '10' : '0';
                 
                $format = [
                                $width,
                                120 +
                                (($quantity_rows * 8) + $extra_by_item_description) +
                                ($discount_global * 3) +
                                $company_name +
                                $company_address +
                                $company_number +
                                $customer_name +
                                $customer_address +
                                $p_order +
                                $legends +
                                $total_exportation +
                                $total_free +
                                $total_unaffected +
                                $total_exonerated +
                                $perception +
                                $total_taxed+
                                $customer_department_id+
                                $total_plastic_bag_taxes
                            ];

                $p['ORIENTATION'] = 'P';
                $pdf->_setPageSize($format, $p['ORIENTATION']);
                $pdf->charset_in='UTF-8';


            } else if($format_pdf === 'a5'){
    
                $company_name      = (strlen($company->name) / 20) * 10;
                $company_address   = (strlen($document->establishment->address) / 30) * 10;
                $company_number    = $document->establishment->telephone != '' ? '10' : '0';
                $customer_name     = strlen($document->customer->name) > '25' ? '10' : '0';
                $customer_address  = (strlen($document->customer->address) / 200) * 10;
                $p_order           = $document->purchase_order != '' ? '10' : '0';
    
                $total_exportation = $document->total_exportation != '' ? '10' : '0';
                $total_free        = $document->total_free != '' ? '10' : '0';
                $total_unaffected  = $document->total_unaffected != '' ? '10' : '0';
                $total_exonerated  = $document->total_exonerated != '' ? '10' : '0';
                $total_taxed       = $document->total_taxed != '' ? '10' : '0';
                $total_plastic_bag_taxes       = $document->total_plastic_bag_taxes != '' ? '10' : '0';
                $quantity_rows     = count($document->items);
    
                $extra_by_item_description = 0;
                $discount_global = 0;
                foreach ($document->items as $it) {
                    if(strlen($it->item->description)>100){
                        $extra_by_item_description +=24;
                    }
                    if ($it->discounts) {
                        $discount_global = $discount_global + 1;
                    }
                }
                $legends = $document->legends != '' ? '10' : '0';
    
    
                $height = ($quantity_rows * 8) +
                        ($discount_global * 3) +
                        $company_name +
                        $company_address +
                        $company_number +
                        $customer_name +
                        $customer_address +
                        $p_order +
                        $legends +
                        $total_exportation +
                        $total_free +
                        $total_unaffected +
                        $total_exonerated +
                        $total_taxed;
                $diferencia = 148 - (float)$height;
    
                $format = [
                    210,
                    $diferencia + $height 
                ];

                $p['ORIENTATION'] = 'P';
                $pdf->_setPageSize($format, $p['ORIENTATION']);
                $pdf->charset_in='UTF-8';
  
    
           } 
           else {
                
                $pdf_font_regular = config('tenant.pdf_name_regular');
                $pdf_font_bold = config('tenant.pdf_name_bold');
    
                if ($pdf_font_regular != false) {
                    $defaultConfig = (new ConfigVariables())->getDefaults();
                    $fontDirs = $defaultConfig['fontDir'];
    
                    $defaultFontConfig = (new FontVariables())->getDefaults();
                    $fontData = $defaultFontConfig['fontdata'];
    
                    $pdf = new Mpdf([
                        'fontDir' => array_merge($fontDirs, [
                            app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                                     DIRECTORY_SEPARATOR.'pdf'.
                                                     DIRECTORY_SEPARATOR.$base_pdf_template.
                                                     DIRECTORY_SEPARATOR.'font')
                        ]),
                        'fontdata' => $fontData + [
                            'custom_bold' => [
                                'R' => $pdf_font_bold.'.ttf',
                            ],
                            'custom_regular' => [
                                'R' => $pdf_font_regular.'.ttf',
                            ],
                        ]
                    ]);
                }
            }
     
    
            if (($format_pdf != 'ticket') AND ($format_pdf != 'ticket_58')) {
                if(config('tenant.pdf_template_footer')) {
                    $html_footer = $template->pdfFooter($base_pdf_template);
                    $pdf->SetHTMLFooter($html_footer);
                } 
            }
  
            
            if ($format_pdf != 'ticket') {
                if(config('tenant.pdf_template_footer')) {
                    $html_footer = $template->pdfFooter($base_pdf_template);
                    $pdf->SetHTMLFooter($html_footer);
                } 

            }
            
            if($format_pdf == 'a4'){
                $pdf->AddPage();
            }
            else if ($format_pdf == 'a5') {
                // dd($format_pdf);
                $pdf->AddPageByArray([
                    'margin-left' => 5,
                    'margin-right' => 5,
                    'margin-top' => 2,
                    'margin-bottom' => 0,
                ]);
            }else if ($format_pdf == 'custom') {
                $pdf->AddPageByArray([
                    'margin-left' => 15,
                    'margin-right' => 15,
                    'margin-top' => 16,
                    'margin-bottom' => 1,
                ]);
            }else{
                $pdf->AddPageByArray([
                    'margin-left' => 1,
                    'margin-right' => 1,
                    'margin-top' => 0,
                    'margin-bottom' => 0,
                ]);
            }

            

            $pdf->WriteHTML($html);
        } 
        
        
        $this->uploadFile($filename, $pdf->output('', 'S'), 'import_documents');
    }
    
    public function uploadFile($filename, $file_content, $file_type) {
        $this->uploadStorage($filename, $file_content, $file_type);
    }

 



    // public function store(ExpenseRequest $request)
    // {
    //     $data = self::merge_inputs($request);

    //     $expense = DB::connection('tenant')->transaction(function () use ($data) {

    //         $doc = Expense::create($data);
    //         foreach ($data['items'] as $row)
    //         {
    //             $doc->items()->create($row);
    //         } 

    //         foreach ($data['payments'] as $row)
    //         {
    //             $doc->payments()->create($row);
    //         }             

    //         return $doc;
    //     });       
 
    //     return [
    //         'success' => true,
    //         'data' => [
    //             'id' => $expense->id,
    //         ],
    //     ];
    // }

}
