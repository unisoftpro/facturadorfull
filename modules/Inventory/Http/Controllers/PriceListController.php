<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\TypeListPrice;
use App\Models\Tenant\ListPriceItem;




class PriceListController extends Controller
{


    public function columns()
    {
        return [
            'description' => 'Producto',
        ];
    }

    public function store(Request $request)
    {
        //$list_type_id = $request->list_type_id;

        //dd($request);

        foreach ($request->items as $item) {

            $data = [   'currency_type_id' =>$item['currency_type_id'] , 'price_fob' => $item['price_fob'], 'factor' => $item['factor'],
                        'price_list' => $item['price_list'], 'discount_one' => $item['discount_one'], 'discount_two' => $item['discount_two'],
                        'discount_three' => $item['discount_three'],'item_id' => $item['item_id'], 'list_type_id' => $item['list_type_id']
                    ];

            $row = ListPriceItem::create( $data );

        }

        return [
            'success' => true,
            'message' => 'Proceso ejecutado correctamente.'
        ];

    }
}
