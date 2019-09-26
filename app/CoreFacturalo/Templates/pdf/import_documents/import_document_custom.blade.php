@php
    $establishment = $document->establishment;
    $items = $document->items;
    $customer = $document->customer;
    $invoice = $document->invoice;
    $document_base = ($document->note) ? $document->note : null;
    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'import_documents'.DIRECTORY_SEPARATOR.'bootstrap.css');
    //dd($items[0]->order->seller_sku);
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    
    $payments = $document->payments;

@endphp
<html>
<head>
    {{--<title>{{ $document_number }}</title>--}}
    <link href="{{ $path_style }}" rel="stylesheet" />
    <style>
        @page {
            margin-header: 1mm; 
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 10mm;
            margin-right: 8mm;
        }
        .product-list{margin-bottom:2px;font-size:11px;}
        .product-list th,.product-list td{background:#F5F5F5;padding:5px 1;}
        .product-return{font-size: 12px;}
        span.title{color: #F58230;font-size: 13px;font-weight: bold;}
        .product-bottom{
            padding: 10px; 
            background: #FF6600;
            color: #ffffff;
            font-size:11px;
        }
        .product-bottom a{color: #ffffff;}
    </style>
</head>
<body> 


<table style="width: 100%;">
    <tr><td width="30%"><img src="{{asset('images/logo.png')}}" class="img-responsive" alt="Cinque Terre"></td>
    <td width="70%" style="text-align:right;">
            <p><strong>Fecha: </strong> {{$document->date_of_issue->format("m-d-Y")}}</p>
            <p><strong>Número  de  órden: </strong>{{$document->order_number}}</p>
            <p><strong>Nota  de  cobranza  # </strong> 100009267530054469 </p>
            <p><small><em class="text-muted">*Noremplazalafacturadelvendedor</em></small></p>
    </td></tr>
</table>
<table style="width: 100%;margin-top:30px;">
    <tr>
        <td width="40%" style="background:#FF6600;padding:20px;color:#ffffff;height:10px;">
            <p><strong>Datos de envío:  </strong></p>
            <p>{{ $customer->name }}</p>
            <p>{{ $customer->address }}</p>
            <p>{{ $items[0]->order->shipping_city}} </p>
            <p><strong>DNI/RUC  </strong>{{ $customer->number }}</p>
        </td>
        <td width="60%" style="text-align:right;background:#F0F0F0;">
        </td>
    </tr>
</table>
<h5>LISTA DE PRODUCTOS</h5>
    <table class="table table-borderless product-list">
        <thead>
        <tr>
            <th style="width:5%">#</th>
            <th style="width:30%">Nombre</th>
            <th style="width:18%">// SKU vendedor</th>
            <th style="width:18%">// Digital SKU </th>
            <th style="width:14%">* Precio</th>
            <th style="width:15%">* Precio pagado</th>
        </tr>
        </thead>
        @foreach($items as $it)
            @if($it->item->internal_id !== 'ENVIO')
            <tr>
                <td>{{ round($it->quantity,0)}}</td>
                <td valign="top">{{$it->item->description}}</td>
                <td valign="top">{{$it->order->seller_sku}}</td>
                <td valign="top">{{$it->order->linio_sku}}</td>
                <td valign="top">* {{round($it->unit_price,2)}}</td>
                <td valign="top">* {{$it->order->paid_price}}</td>
            </tr> 
            @endif
        @endforeach
    </table>


    <table style="width: 100%;" class="total">
        <tr>
            <td width="20%"><img src="{{asset('images/help.png')}}" class="img-responsive" alt="help"></td>
            <td width="30%" style="font-size:12px;padding-right:50px;line-height:20px;">
                <p>Compras 100% seguras <br>¿NO TE CONVENCIÓ? Devuélvelo gratis y sin preguntas ¿TIENES DUDAS? Consulta nuestras preguntas frecuentes en </p>
                <p><a href="{!! url('/') !!}">{!! url('/') !!}</a></p>
            </td>
            @php
                $envio = $document->items->where('order','!=',null)->sum('order.shipping_fee');
            @endphp
            <td width="45%" style="text-align:right;background:#F0F0F0;padding:10px;line-height: 30px;">
                <p><strong>Subtotal : </strong>{{number_format($document->total - $envio ,2)}}</p>
                <p><strong>Cupón : </strong>0.00</p>
                <p><strong>Total productos : </strong>{{number_format($document->total,2)}}</p>
                <p><strong>Costo de envío : </strong>{{number_format($envio, 2)}}</p>
            </td>
        </tr>
    </table>

    <br>

    <div class="row product-question text-center">
        <p><em><strong>¿Compraste vario sproductos en la misma orden?</strong>, recuerda que los recibirás por separado si eran de proveedores diferentes. Para verificar el envío de cada producto o si tienes dudas ingresa a "Mis Pedidos"</em></p>
    </div>

    <table class="product-return" style="width: 90%; margin:20px auto;" border="0">
        <tr>
            <td colspan="2">
                <div class="row text-center">
                <p><strong>En caso de devolución, recuerda que cuentas con 10 dias para realizarla siguiendo los siguientes pasos : </strong></p>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%" valign="top">
                <table border="0" style="width:100%;">
                    <tr>
                        <td width="35%" align="right" ><img src="{{asset('images/computer.png')}}" class="img-responsive" alt="" style="width:80px;"></td>
                        <td><span class="title">1. CONECTATE</span></td>
                    </tr>
                    <tr>
                    <td colspan="2" style="padding:5px;"><p><strong>Inicia session en Digitalcrazy,</strong> ingresa a "Mis Pedidos", elige el producto que deseas devolver y dale click al boton "Devolver"</p></td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <table border="0" style="width:100%">
                    <tr>
                        <td width="30%" align="right" ><img src="{{asset('images/product-box.png')}}" class="img-responsive" alt="" style="width:80px;"></td>
                        <td><span class="title" style="padding:5px;">3. GUARDA TU PRODUCTO <br>EN SU CAJA ORIGINAL</span></td>
                    </tr>
                    <tr>
                    <td colspan="2"><p><strong>Protegiendola de cualquier dano que pueda sufrir,</strong> Dentro se debera de encontrar todos los accesorios y documentacion recibida, el producto no debe tener senales de uso.</p></td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td width="50%" valign="top">
                <table border="0" style="width:100%">
                    <tr>
                        <td width="30%" align="right" ><img src="{{asset('images/printer.png')}}" class="img-responsive" alt="" style="width:80px;"></td>
                        <td><span class="title">2. DESCARGA E IMPRIME <br>TU GUIA DE DEVOLUCION</span></td>
                    </tr>
                    <tr>
                    <td colspan="2" style="padding:5px;"><p><strong>Inicia session en Digitalcrazy,</strong> ingresa a "Mis Pedidos", elige el producto que deseas devolver y dale click al boton "Devolver"</p></td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <table border="0" style="width:100%;">
                    <tr>
                        <td width="35%" align="right" ><img src="{{asset('images/truck.png')}}" class="img-responsive" alt="" style="width:80px;"></td>
                        <td><span class="title">4. EVIANOS TU PAUETE</span></td>
                    </tr>
                    <tr>
                    <td colspan="2" style="padding:5px;"><p><strong>Lleva el producto empacado a cualquier oficina principal de Olva Courier ,</strong> la atencion ed de lunes a vierned hasta las 5pm y ssabados hasta las 12:00pm <br>En cuanto lo recibamos, tardaremo un maxio de 3 diao habiled en revisarlo y aprobar tu devolucion</p></td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <div class="row product-bottom text-center">
        *Aplican condiciones, para mayor información y verificar que tu producto aplica para devolución, visita <a href="{!! url('/') !!}/dev">{!! url('/') !!}/dev</a>
    </div>

</body>
</html>