@php


@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte Ingreso</title>
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
                        <p><strong>Almacén: </strong>{{$record->warehouse->description}}</p>
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
                        <p>Um Costo Orig.</p>
                    </td>
                    <td class="td-custom">
                       <p>Cantidad</p>
                    </td>
                    <td class="td-custom">
                       <p>Sub-Total</p>
                    </td>
                    <td class="td-custom">
                       <p>Total</p>
                    </td>
                </tr>
            </table>
        </div>

        <p>MATERIAS PRIMAS</p>

        @if($record->items->count())

            @inject('serviceFamily', 'App\Services\ItemReportFamilyService')

            @php
                $rows = $serviceFamily->GroupedByFamily( $record->id );
            @endphp
            <div>
                    @foreach ($rows as $x => $x_value)
                    <p> {{ $x }} </p>
                    <table>
                            @foreach($x_value as $it)
                                <tr>
                                    <td>{{ $it->code }}</td>
                                    <td> {{ $it->name }} </td>
                                    <td> {{ $it->unit }} </td>

                                    <td> {{ $it->quantity }} </td>
                                    <td> {{ $it->sub_total }} </td>
                                    <td> {{ $it->total }} </td>
                                </tr>
                            @endforeach
                    </table>
                    @endforeach
            </div>
        @else

        @endif
    </body>
</html>
