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
                        <th class="td-custom">
                            <strong>Item</strong>
                          </th>
                          <th class="td-custom">
                            <strong> Articulo</strong>
                          </th>
                          <th style="width:7%">
                            <strong>Descripción </strong>
                          </th>
                          <th class="td-custom">
                            <strong>  UM</strong>
                          </th>
                          <th class="td-custom">
                            <strong>  Cantidad</strong>
                          </th>
                          <th class="td-custom">
                            <strong> Costo Unitario.</strong>
                          </th>
                          <th class="td-custom">
                            <strong> Sub-Total</strong>
                          </th>
                    </tr>

                </thead>


            </table>
        </div>
    </body>
</html>
