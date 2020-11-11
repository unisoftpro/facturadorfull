@php


@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte Ordenes de Compras</title>
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
                font-weight: bold;
            }
            .page-break {
                page-break-inside:avoid; page-break-after:auto;
            }



        </style>
    </head>
    <body>

        <div>
            <table style="border:1px solid black">
                <tr>
                    <th colspan="2" style="text-align: center !important"  class="title">
                        <strong>Reporte de Orden de Compra </strong>
                    </th>
                </tr>
                <tr>
                    <th class="td-custom">
                        Compañia <strong>: {{$company->name}}</strong>
                    </th>
                </tr>
            </table>
        </div>

        <div style="margin-top:0px; margin-bottom:20px;">
            <table>
                <thead style="border: 1px solid black">
                    <tr>
                        <th class="td-custom" style="text-align: center;width: 40px">
                            <strong>Item</strong>
                          </th>
                          <th class="td-custom" style="text-align: center;width: 50px">
                            <strong> Articulo</strong>
                          </th>
                          <th style="text-align: center;width: 200px">
                            <strong>Descripción </strong>
                          </th>
                          <th class="td-custom" style="text-align: center;width: 50px">
                            <strong>  UM</strong>
                          </th>
                          <th class="td-custom" style="text-align: center;width: 50px">
                            <strong>  Cantidad</strong>
                          </th>
                          <th class="td-custom" style="text-align: center;width: 100px">
                            <strong> Costo Unitario.</strong>
                          </th>
                          <th class="td-custom" style="text-align: center;width: 100px">
                            <strong> Sub-Total</strong>
                          </th>
                    </tr>
                </thead>
            </table>
        </div>
        @foreach($records as $key => $value)
        <div class="page-break">
        <table style="border:1px solid black;">
            <tr>
                <th class="td-custom">
                    Proveedor<strong>:{{$value['supplier']}}</strong>
                </th>
                <th colspan="2"></th>
                <th>
                    N° Orden Compra<strong>:{{$value['number']}}</strong>
                </th>
            </tr>
            <tr>
                <th class="td-custom">
                    Moneda<strong>:{{$value['currency_type']}}</strong>
                </th>
                <th class="td-custom">
                    Usuario<strong>:{{$value['userName']}}</strong>
                </th>
                <th class="td-custom">
                    Fecha<strong>:{{$value['date_of_issue']->format('Y-m-d')}}</strong>
                </th>
                <th class="td-custom">
                    Fecha Doc<strong>:{{$value['date_of_issue']->format('Y-m-d')}}</strong>
                </th>
            </tr>
            <tr>
                <th class="td-custom">
                    Nro Ingreso<strong>:{{$value['currency_type']}}</strong>
                </th>
                <th colspan="1">
                    Nro Factura<strong>:-</strong>
                </th>
                <th colspan="1">
                    Nro OT<strong>:{{$value['work_order']}}</strong>
                </th>
                <th colspan="1">
                    Concepto<strong>:{{$value['purchase_order_state']}}</strong>
                </th>
            </tr>
        </table>
        <table style="margin-bottom: 10px !important">
            @foreach ($value['items'] as $items)
            <thead style="border: 1px solid black">
                <tr>
                    <th class="celda" style="width: 40px">
                        {{$loop->iteration}}
                    </th>
                    <th class="celda" style="width: 50px">
                        {{$items['internal_id']}}
                    </th>
                    <th class="celda" style="width: 200px;text-align: left !important">
                        {{$items['description']}}
                    </th>
                    <th class="celda" style="width: 50px">
                        {{$items['unit_type_id']}}
                    </th>
                    <th class="celda" style="width: 50px">
                        {{$items['quantity']}}
                    </th>
                    <th class="celda" style="width: 100px;text-align: right !important">
                        {{$items['unit_price']}}
                    </th>
                    <th class="celda" style="width: 100px;text-align: right !important">
                        {{$items['total']}}
                    </th>
                </tr>
            </thead>
            <tfoot style="border:1px solid black">
                <tr>
                    <td colspan="3" style="color: white">.</td>
                    <td colspan="4" style="border-left: 1px solid black"></td>
                </tr>
                <tr>
                    <td colspan="3" ></td>
                    <td colspan="2" style="border-left: 1px solid black" >Total Sin IGV: S/</td>
                    <td colspan="2" style="text-align: right !important">{{$value['total_value']}}</td>
                </tr>
                <tr>
                    <td colspan="3" ></td>
                    <td colspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black" >IGV: S/</td>
                    <td colspan="2" style="text-align: right !important;border-bottom: 1px solid black">{{$value['total_igv']}}</td>
                </tr>
                <tr>
                    <td colspan="3" ></td>
                    <td colspan="2" style="border-left: 1px solid black;" >Total: S/</td>
                    <td colspan="2" style="text-align: right !important;">{{$value['total']}}</td>
                </tr>

            </tfoot>
            @endforeach
        </table>
    </div>
        @endforeach

    </body>
</html>
