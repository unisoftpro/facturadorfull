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
            }

            .celda {
                text-align: center;
                padding: 5px;
                border: 0.1px solid black;
            }

            th {
                padding: 5px;
                text-align: center;
            }

            .title {
                font-weight: bold;
                padding: 5px;
                font-size: 15px !important;
                text-decoration: none;
            }

            p>strong {
                margin-left: 5px;
                font-size: 12px;
            }

            thead {
                font-weight: bold;
                text-align: center;
                border: 1px solid #1F1B8E;

            }
            .td-custom { line-height: 0.1em; }
        </style>
    </head>
    @php
    $signo_moneda = $record->currency_type->symbol;
    @endphp
    <body style="color: #1F1B8E">
        <div style="margin-bottom:20px;">
            <table style="border-bottom: 1px solid #1F1B8E;padding-bottom: -12px">
                <tr>
                <td class="td-custom">
                        <p>Empresa: <strong>{{$company->name}} </strong></p>
                    </td>
                    <td class="td-custom">
                        <p>Hora: <?php $hoy = date('h:i s A');
                            print_r($hoy);?> </p>
                    </td>
                </tr>
                <tr>
                    <td class="td-custom">
                        <p>Almacén: <strong>{{$record->warehouse->description}} </strong></p>
                    </td>
                    <td class="td-custom">
                        <p>Fecha: <?php $hoy = date('d/m/y');
                            print_r($hoy);?> </p>
                    </td>

                </tr>

            </table>
        </div>
        <div style="margin-top: -22px">
            <p align="center" class="title"><strong>Ingreso Nro {{ str_pad($record->number, 7, "0", STR_PAD_LEFT) }}</strong></p>
        </div>
        <div >
            <table>
                <tr>
                    <td class="td-custom">
                        <p> Motivo: {{$record->warehouse_income_reason_id}} {{$record->warehouse_income_reason->description}}  </p>

                    </td>
                    <td class="td-custom">
                        <p><strong>Fecha Ingreso: </strong>{{$record->date_of_issue}}</p>
                    </td>
                </tr>
                <tr>
                    <td class="td-custom">
                        <p> Proveedor: {{$record->supplier_id}} <strong> {{$record->person->name}}</strong> </p>

                    </td>
                    <td class="td-custom">
                        <p><strong>Moneda: {{$record->currency_type->symbol}} {{$record->currency_type->description}}</strong> </p>
                    </td>
                </tr>
                <tr>
                    <td class="td-custom">
                        <p> Obs: {{$record->observation}} </p>

                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top:0px; margin-bottom:20px;">
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
                            <td> <strong>  {{ $record->retail_price}}</strong></td>
                            <td><strong> {{ $record->letter_price}}</strong> </td>
                        </tr>
                    @endforeach
                </tbody>
                <tr >

                    <td ></td>
                    <td ></td>
                    <td style="text-align: right;border-top: 1px solid #1F1B8E" colspan="1"><strong> Total:  {{$signo_moneda}} </strong></td>
                    <td style="border-top: 1px solid #1F1B8E"></td>
                    <td style="border-top: 1px solid #1F1B8E"></td>
                    <td style="border-top: 1px solid #1F1B8E" colspan="1"><strong>{{ $total }} </strong></td>
                </tr>
            </table>
        </div>
        <div style="width: 100%;margin-top: 50px">
            <div style="float: left;width: 40%;margin-right: 5%;margin-left: 5%">
                <center>
                    <hr>
                    <p>
                        <span style="font-size: 12px;color: #1F1B8E">
                            Recibido por:
                        </span>
                    </p>
                </center>
            </div>
            <div style="float: left;width: 40%;margin-right: 5%;margin-left: 5%">
                <center>
                    <hr>
                    <p>
                        <span style="font-size: 12px;color: #1F1B8E">
                            Vo. Bo:
                        </span>
                    </p>
                </center>
            </div>
        </div>

    </body>
</html>
