@php


@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Lista de Precios</title>
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
            <table >
                <tr>
                    <th colspan="2" style="text-align: center !important"  class="title">
                        <strong>LISTA DE PRECIOS</strong><br>
                        @if($igv==="2")
                        <strong>SIN IGV</strong>
                        @else
                        <strong>CON IGV</strong>
                        @endif
                    </th>
                </tr>
            </table>
        </div>
        <div style="margin-top:0px; margin-bottom:20px;">
            <table>
                <thead style="border: 1px solid black;border-radius: 5px !important;">
                    <tr style="vertical-align: middle">
                        <th class="td-custom" style="padding: 10px">
                            Código
                          </th>
                          <th class="td-custom" style="padding: 10px">
                             Descripcion
                          </th>

                          <th class="td-custom" style="padding: 10px">
                             Cod.Equivalente
                          </th>
                          <th class="td-custom" style="padding: 10px">
                              UM
                          </th>
                          <th class="td-custom" style="padding: 10px">
                             Precio Lista
                          </th>
                          <th class="td-custom" style="padding: 10px">
                             Stock
                          </th>
                    </tr>
                </thead>
            </table>
        </div>
    </body>
</html>
