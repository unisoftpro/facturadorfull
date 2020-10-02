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
                border: 1px solid black;
            }

            .celda {
                text-align: center;
                padding: 5px;
                border: 0.1px solid black;
            }

            th {
                padding: 5px;
                text-align: center;
                border-color: #0088cc;
                border: 0.1px solid black;
            }

            .title {
                font-weight: bold;
                padding: 5px;
                font-size: 20px !important;
                text-decoration: underline;
            }

            p>strong {
                margin-left: 5px;
                font-size: 12px;
            }

            thead {
                font-weight: bold;
                background: #0088cc;
                color: white;
                text-align: center;
            }
            .td-custom { line-height: 0.1em; }
        </style>
    </head>
    <body>
        <div>
            <p align="center" class="title"><strong>Ingreso Nro {{ str_pad($record->number, 7, "0", STR_PAD_LEFT) }}</strong></p>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
                <tr>
                    <td class="td-custom">
                        <p><strong>Almacén Destino: </strong>{{$record->warehouse_expense->description}}</p>
                        <p><strong>Almacén Salida: </strong>{{$record->warehouse_destination->description}}</p>

                    </td>
                </tr>
                <tr>
                    <td class="td-custom">
                        <p><strong>Fecha Ingreso: </strong>{{$record->date_of_issue}}</p>
                    </td>
                    <td class="td-custom">
                        <p><strong>Proveedor: </strong>{{$record->person->name}}</p>
                    </td>
                </tr>

                <tr>
                    <td class="td-custom">
                        <p><strong>Moneda: </strong>{{$record->currency_type_id}}</p>
                    </td>
                    <td class="td-custom">
                        <p><strong>Concepto: </strong>{{$record->observation}}</p>
                    </td>
                </tr>
                <tr>
                    @if($record->work_order_id)

                        <td  class="td-custom">
                            <p><strong>Ord. Trab: </strong>{{ str_pad($record->work_order->number, 7) }}</p>
                        </td>

                    @endif

                    @if($record->purchase_order_id)
                        <td class="td-custom">
                            <p><strong>Ord. Comp: </strong>{{  $record->purchase_order->prefix.'-'.$record->purchase_order->id }} </p>
                        </td>
                    @endif
                </tr>
            </table>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
                <tr>
                    <td class="td-custom">
                       <p>Item</p>
                    </td>
                    <td class="td-custom">
                       <p>Descripcion</p>
                    </td>
                    <td class="td-custom">
                       <p>UM</p>
                    </td>
                    <td class="td-custom">
                        <p>Costo Orig.</p>
                    </td>
                    <td class="td-custom">
                       <p>Cant.</p>
                    </td>
                    <td class="td-custom">
                       <p>Sub-Total</p>
                    </td>
                </tr>
            </table>
        </div>

        @if($record->items->count())

            @inject('serviceFamily', 'App\Services\ItemReportFamilyService')

            @php
                $rows = $serviceFamily->GroupedByFamilyExpense( $record->id );
                $total_sin_igv = 0;
                $total_con_igv = 0;
            @endphp
            <div>
                    @foreach ($rows as $family => $family_value)
                    <P> {{ $family }} </P>

                        @foreach($family_value as $line => $line_value)
                            &nbsp;&nbsp;<span> {{ $line }} </span> <br>

                            <table>
                                    @foreach($line_value as $it)
                                        @php
                                            $subtotal = $it->quantity * $it->unit_value ;
                                            $total_sin_igv += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $it->code }}</td>
                                            <td> {{ $it->name }} </td>
                                            <td> {{ $it->unit }} </td>

                                            <td> {{ $it->unit_value }} </td>
                                            <td> {{ $it->quantity }} </td>
                                            <td> {{ $it->quantity * $it->unit_value  }} </td>
                                        </tr>
                                    @endforeach
                            </table>

                        @endforeach


                    @endforeach
            </div>
            <br>
            <table>
                <tr>
                    <td style="text-align: right;" >Total Sin IGV:</td>
                    <td> {{ $total_sin_igv}}</td>
                </tr>
                <tr>
                    <td style="text-align: right;" >Total Con IGV:</td>
                    <td> {{ round($total_sin_igv * 1.18, 2)}} </td>
                </tr>
            </table>
        @else

        @endif
    </body>
</html>
