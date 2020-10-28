<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\TypeListPrice;
use App\Models\Tenant\ListPriceItem;
use App\Models\Tenant\Item;
use Modules\Inventory\Http\Resources\ListPriceItems;



class PriceListController extends Controller
{

    public function index()
    {
        return view('inventory::list-price-item.index');
    }
    public function columns()
    {
        return [
            'description' => 'Producto',
        ];
    }
    public function records(){
        //$record = Item::join('list_price_item',);
        $record = Item::join('list_price_item', 'items.id', '=', 'list_price_item.item_id')
        ->join('type_list_prices', 'type_list_prices.id', '=', 'list_price_item.list_type_id')
        ->select(
            'items.id','items.name','list_price_item.currency_type_id','list_price_item.list_type_id','type_list_prices.description'
        )
        ->groupBy('items.id', 'items.name', 'list_price_item.currency_type_id', 'list_price_item.list_type_id', 'type_list_prices.description')
        ->orderBy('items.name', 'asc')
        ->orderBy('list_price_item.list_type_id','asc')
        ->orderBy('list_price_item.currency_type_id','asc');
        return new ListPriceItems($record->paginate(config('tenant.items_per_page')));
        //dd($record);
        //return compact('record');
    }
    public function store(Request $request)
    {
        //$list_type_id = $request->list_type_id;

        //dd($request);

        foreach ($request->items as $item) {

            $data = [
                'currency_type_id' => $item['currency_type_id'], 'price_fob' => $item['price_fob'], 'factor' => $item['factor'],
                'price_list' => $item['price_list'], 'discount_one' => $item['discount_one'], 'discount_two' => $item['discount_two'],
                'discount_three' => $item['discount_three'], 'item_id' => $item['item_id'], 'list_type_id' => $item['list_type_id']
            ];

            $row = ListPriceItem::create($data);
        }

        return [
            'success' => true,
            'message' => 'Proceso ejecutado correctamente.'
        ];
    }
    public function getitems($item,$currency,$listtype){
        //dd($item,$currency,$listtype);
        $items = Item::where('id',$item)->get();
        $listpriceitems = ListPriceItem::where('item_id',$item)->where('currency_type_id',$currency)->where('list_type_id',$listtype)->get();
        //dd($items,$listpriceitems);
        return compact('items','listpriceitems');
    }
}
