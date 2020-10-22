@php


@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte Salida</title>
        <style>
            html {
                font-family: sans-serif;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-spacing: 0;
            }

            .celda {
                text-align: center;
                padding: 5px;
                border: 0.1px solid black;
            }

            th {
                padding: 5px;
                text-align: left;

            }

            .title {
                padding: 5px;
                font-size: 20px !important;
                text-decoration: none;
            }

            th>strong {
                margin-left: 5px;
                font-size: 12px;
            }
        </style>
    </head>
    <body>

        <div >
            <table style="border:1px solid black">
                <tr>
                    <th colspan="2" style="text-align: center !important"  class="title">
                        <strong>Salida Nro {{ str_pad($record->number, 7, "0", STR_PAD_LEFT) }}</strong>
                    </th>
                </tr>
                <tr>
                    <th class="td-custom">
                        <strong>Almacén Salida: </strong>{{$record->warehouse_expense->description}}<br>
                        <strong>Almacén Destino: </strong>{{$record->warehouse_destination->description}}
                    </th>
                    <th class="td-custom">
                        <strong>Fecha Ingreso: </strong>{{$record->date_of_issue}}
                    </th>
                </tr>
                <tr>
                    <th class="td-custom">
                        <strong>Concepto: </strong>{{$record->warehouse_expense_reason->description}}
                    </th>
                    <th class="td-custom">
                        <strong>Documento Ref: </strong>
                    </th>

                </tr>
                <tr>
                    <th class="td-custom">
                        <strong>Proveedor: </strong>{{$record->person->name}}
                    </th>
                    @if($record->work_order_id)

                        <th  class="td-custom">
                            <strong>Ord. Trab: </strong>{{ str_pad($record->work_order->number, 7, "0", STR_PAD_LEFT) }}
                        </th>

                    @endif
                </tr>
                <tr>
                    <th class="td-custom">
                        <strong>Moneda: </strong>{{$record->currency_type->symbol}} {{$record->currency_type->description}}
                    </th>

                    @if($record->purchase_order_id)
                        <th class="td-custom">
                            <strong>Ord. Comp: </strong>{{  $record->purchase_order->prefix.'-'.$record->purchase_order->id }}
                        </th>
                    @endif
                </tr>
            </table>
        </div>
        <div style="margin-top:0px; margin-bottom:20px;">
            <table>
                <thead style="border: 1px solid black">
                    <tr>
                        <th class="td-custom" style="width:10%">
                            Item
                          </th>
                          <th class="td-custom" style="width:35%">
                             Descripcion
                          </th>
                          <th style="width:8%">

                          </th>
                          <th style="width:7%">

                          </th>
                          <th class="td-custom" style="width:5%">
                             UM
                          </th>
                          <th class="td-custom">
                              Costo Orig.
                          </th>
                          <th class="td-custom">
                             Cant.
                          </th>
                          <th class="td-custom">
                             Sub-Total
                          </th>
                    </tr>
                </thead>
                <tbody style="border-right: 0px !important">
                    @if($record->items->count())

                        @inject('serviceFamily', 'App\Services\ItemReportFamilyService')

                        @php
                            $rows = $serviceFamily->GroupedByFamilyExpense( $record->id );
                            $total_sin_igv = 0;
                            $total_con_igv = 0;
                        @endphp
                        @foreach ($rows as $family => $family_value)
                        <tr>
                            <td colspan="2" style="border-bottom: 1px solid black;padding: 8px !important;">
                                {{ $family }}
                            </td>
                        </tr>
                            @foreach($family_value as $line => $line_value)
                            <tr>
                                <td colspan="1">

                                </td>
                                <td colspan="2" style="border-bottom: 1px solid black;padding: 8px !important;">
                                    {{ $line }}
                                </td>
                            </tr>
                                @foreach($line_value as $it)
                                    @php
                                    $subtotal = $it->quantity * $it->unit_value ;
                                    $total_sin_igv += $subtotal;
                                    @endphp
                                    <tr>
                                        <td>{{ $it->code }}</td>
                                        <td style="text-align: left;padding-left: 20px"> {{ $it->name }} </td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right;"> {{ $it->unit }} </td>

                                        <td style="text-align: right;"> {{ $it->unit_value }} </td>
                                        <td style="text-align: right;"> {{ $it->quantity }} </td>
                                        <td style="text-align: right;"> {{ $it->quantity * $it->unit_value  }} </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                     @else
                     @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5"></td>
                        <td colspan="2" style="padding: 5px !important;text-align: left;border-top: 1px solid black;border-left: 1px solid black" ><strong>Total Sin IGV: </strong> </td>
                        <td style="padding: 5px !important;text-align: right;border-top: 1px solid black;border-right: 1px solid black"> <strong> {{ $total_sin_igv}}</strong></td>
                    </tr>
                    <tr >
                        <td colspan="1"></td>
                        <td colspan="1" style="padding: 5px">
                            <span style="text-decoration: underline;font-weight: bold;">{{$record->user->name}}</span><br>
                            Usuario Almacen
                        </td>
                        <td colspan="2" style="padding: 5px">
                            <span style="text-decoration: underline;font-weight: bold;"></span><br>
                            Entregado a
                        </td>
                        <td colspan="1"></td>
                        <td colspan="2" style="padding: 5px !important;text-align: left;border-bottom: 1px solid black;border-left: 1px solid black" > <strong>Total Con IGV: </strong></td>
                        <td style="padding: 5px !important;text-align: right;border-bottom: 1px solid black;border-right: 1px solid black"> <strong>{{ round($total_sin_igv * 1.18, 2)}}  </strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </body>
</html>
