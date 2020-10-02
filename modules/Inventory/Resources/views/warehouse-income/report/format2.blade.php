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
                text-align: center;
            }
            .td-custom { line-height: 0.1em; }
        </style>
    </head>
    <body>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
                <tr>
                <td class="td-custom">
                        <p><strong>Empresa: </strong>{{$company->name}}</p>
                    </td>
                    <td class="td-custom">
                        <p><strong>Almacén: </strong>{{$record->warehouse->description}}</p>
                    </td>
                </tr>
                <tr>
                    <td class="td-custom">
                        <p><strong>Fecha: </strong>{{$record->date_of_issue}}</p>
                    </td>
                </tr>

            </table>
        </div>
        <div>
            <p align="center" class="title"><strong>Ingreso Nro {{ str_pad($record->number, 7, "0", STR_PAD_LEFT) }}</strong></p>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
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
                        <p><strong>Motivo: </strong>{{$record->observation}}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripcion</th>
                        <th>UM</th>

                        <th>Cantidad</th>
                        <th>Precio x Factor</th>
                        <th>Letra</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach($record->items as $record)
                        @php
                            $total += $record->retail_price;
                        @endphp
                        <tr>
                            <td>{{ $record->relation_item->internal_id }}</td>
                            <td> {{ $record->item->description}}</td>
                            <td> {{ $record->item->unit_type_id}}</td>

                            <td> {{ $record->quantity}}</td>
                            <td> {{ $record->retail_price}}</td>
                            <td> {{ $record->letter_price}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <table>
                <tr>
                    <td style="text-align: right;" colspan="5">Total:</td>
                    <td>{{ $total }}</td>
                </tr>
            </table>
        </div>


    </body>
</html>
