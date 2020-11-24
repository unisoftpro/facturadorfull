@php


@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte de Saldos Volorizados</title>
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
                        <strong>REPORTE DE SALDOS CANTIDADES VALORIZADAS</strong><br>
                        <strong>Almacen:</strong>{{$warehouse->description}}<br>
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
                            Moneda
                         </th>
                         <th class="td-custom" style="padding: 10px">
                            Saldo
                         </th>
                          <th class="td-custom" style="padding: 10px">
                              Precio FOB
                          </th>
                          <th class="td-custom" style="padding: 10px">
                            Total Art.x Mon
                          </th>
                          <th class="td-custom" style="padding: 10px">
                            Total Art.En Soles
                          </th>
                    </tr>
                </thead>
                <tbody style="border-right: 0px !important">
                    @if(count($itemsgroup))
                        @inject('serviceFamily', 'App\Services\ItemReportFamilyService')
                        @php
                        $rows = $serviceFamily->GroupeByFamilySaldos($itemsgroup);
                        //dd($rows);
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
                                @foreach($line_value as $key => $it)
                                        <tr>
                                            <td >{{ $it->{'item_code'} }}</td>
                                            <td > {{ $it->{'description'} }} </td>
                                            <td >{{ $it->{'item_code'} }}</td>
                                            <td > {{ $it->{'currency_type_id'} }} </td>
                                            <td > {{ $it->{'saldos'} }} </td>
                                            <td > {{ $it->{'price_fob'} }} </td>
                                            <td > {{ $it->{'Totalart'} }} </td>
                                            <td > {{ $it->{'Totalart'} }} </td>
                                        </tr>
                                    @endforeach
                            @endforeach
                        @endforeach
                    @else
                    @endif
                </tbody>
            </table>
        </div>
    </body>
</html>
