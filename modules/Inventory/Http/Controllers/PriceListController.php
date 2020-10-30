<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Catalogs\CurrencyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\TypeListPrice;
use App\Models\Tenant\ListPriceItem;
use App\Models\Tenant\Item;
use App\Models\Tenant\Person;
use Modules\Inventory\Http\Resources\ListPriceItems;
use Modules\Inventory\Http\Resources\TypeListPriceCollection;
use Modules\Inventory\Models\WarehouseIncomeItem;
use Modules\Inventory\Models\WarehouseIncomeReason;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\PurchaseOrderItem;

class PriceListController extends Controller
{

    public function index()
    {
        return view('inventory::list-price-item.index');
    }
    public function index_type()
    {
        return view('inventory::type-list-price.index');
    }
    public function columns()
    {
        return [
            'description' => 'Producto',
        ];
    }
    public function records()
    {
        //$record = Item::join('list_price_item',);
        $record = Item::join('list_price_item', 'items.id', '=', 'list_price_item.item_id')
            ->join('type_list_prices', 'type_list_prices.id', '=', 'list_price_item.list_type_id')
            ->join('cat_currency_types', 'cat_currency_types.id', '=', 'list_price_item.currency_type_id')
            ->select(
                'cat_currency_types.description AS currencydescription',
                'items.id',
                'items.name',
                'list_price_item.currency_type_id',
                'list_price_item.list_type_id',
                'type_list_prices.description'


            )
            ->groupBy('items.id', 'items.name', 'list_price_item.currency_type_id', 'list_price_item.list_type_id', 'type_list_prices.description', 'cat_currency_types.description')
            ->orderBy('items.name', 'asc')
            ->orderBy('list_price_item.list_type_id', 'asc')
            ->orderBy('list_price_item.currency_type_id', 'asc');
        return new ListPriceItems($record->paginate(config('tenant.items_per_page')));
        //dd($record);
        //return compact('record');
    }
    public function recordstypelist()
    {
        $record = TypeListPrice::select('id', 'description');
        //dd($listType);
        return new TypeListPriceCollection($record->paginate(config('tenant.items_per_page')));
    }
    public function combo()
    {
        $items2 = $this->table('items');
        $currency = CurrencyType::all();
        $listType = TypeListPrice::all();
        return compact('items2', 'currency', 'listType');
    }
    public function store(Request $request)
    {
        //$list_type_id = $request->list_type_id;

        //dd($request);

        foreach ($request->items as $item) {

            if ($item['id'] != null) {
                $data = [

                    'currency_type_id' => $item['currency_type_id'], 'price_fob' => $item['price_fob'], 'factor' => $item['factor'],
                    'price_list' => $item['price_list'], 'discount_one' => $item['discount_one'], 'discount_two' => $item['discount_two'],
                    'discount_three' => $item['discount_three'], 'item_id' => $item['item_id'], 'list_type_id' => $item['list_type_id']

                ];
                $row = ListPriceItem::where(['id' => $item['id']])->update($data);
                //dd($data);

            } else {
                $data = [
                    'currency_type_id' => $item['currency_type_id'], 'price_fob' => $item['price_fob'], 'factor' => $item['factor'],
                    'price_list' => $item['price_list'], 'discount_one' => $item['discount_one'], 'discount_two' => $item['discount_two'],
                    'discount_three' => $item['discount_three'], 'item_id' => $item['item_id'], 'list_type_id' => $item['list_type_id']

                ];
                $row = ListPriceItem::create($data);
            }
        }

        return [
            'success' => true,
            'message' => 'Proceso ejecutado correctamente.'
        ];
    }
    public function destroy($id)
    {
        ListPriceItem::where('id', $id)->delete();
        return [
            'success' => true,
            'message' => 'Proceso ejecutado correctamente.'
        ];
    }
    public function destroytypelist($id)
    {
        TypeListPrice::where('id', $id)->delete();
        return [
            'success' => true,
            'message' => 'Proceso ejecutado correctamente.'
        ];
    }
    public function storetypelist(Request $request)
    {

        $validar = TypeListPrice::where('id', $request->id)->get();

        if (count($validar)>0) {
            TypeListPrice::where('id', $request->id)->update(['description' => $request->description]);
        } else {
            $data = [
                'id' => $request->id, 'description' => $request->description
            ];
            TypeListPrice::create($data);
        }
        return [
            'success' => true,
            'message' => 'Proceso ejecutado correctamente.'
        ];
    }
    public function getIdType()
    {
        $typelist = TypeListPrice::all();

        $idLat = $typelist->last();
        $id = (int) $idLat->id + 1;
        return compact('id');
    }

    public function getitems($item, $currency, $listtype)
    {
        //dd($item,$currency,$listtype);
        $items = Item::where('id', $item)->get();
        $previous_cost = $this->previousCost($item);
        $items2 = $this->table('items');
        $listpriceitems = ListPriceItem::where('item_id', $item)->where('currency_type_id', $currency)->where('list_type_id', $listtype)->get();
        //dd($items,$listpriceitems);
        return compact('items2', 'items', 'listpriceitems', 'previous_cost');
    }
    public function previousCost($item_id)
    {

        $purchase_order_item = PurchaseOrderItem::where('item_id', $item_id)->latest('id')->first();

        if ($purchase_order_item) {
            return [
                'previous_cost' => $purchase_order_item->unit_price,
                'previous_currency_type_id' => $purchase_order_item->purchase_order->currency_type_id,
            ];
        }

        return [
            'previous_cost' => 0,
            'previous_currency_type_id' => null,
        ];
    }
    public function item_tables()
    {

        $items = $this->table('items');
        $warehouse_income_reasons = WarehouseIncomeReason::whereIn('id', ['103', '104'])->get();
        $type_list_prices = TypeListPrice::all();
        $currency_types = CurrencyType::whereActive()->get();
        return compact('items', 'warehouse_income_reasons', 'type_list_prices', 'currency_types');
    }
    public function getAdditionalValues($item_id)
    {

        $record = WarehouseIncomeItem::where('item_id', $item_id)
            ->whereHas('warehouse_income', function ($q) {
                $q->whereIn('warehouse_income_reason_id', ["103", "104"]);
            })
            ->latest('id')
            ->first();

        if ($record) {
            return [
                'last_purchase_price' => $record->list_price,
                'last_factor' => $record->sale_profit_factor,
            ];
        }

        return [
            'last_purchase_price' => 0,
            'last_factor' => 0,
        ];
    }
    public function table($table)
    {

        $data = [];

        switch ($table) {
            case 'purchase_orders':

                $data = PurchaseOrder::get()->transform(function ($row) {
                    return [
                        'id' => $row->id,
                        'number' => $row->id,
                    ];
                });

                break;

            case 'suppliers':

                $data = Person::whereType('suppliers')->orderBy('name')->get()->transform(function ($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number . ' - ' . $row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'email' => $row->email,
                        'identity_document_type_id' => $row->identity_document_type_id,
                    ];
                });

                break;


            case 'items':

                $data = Item::orderBy('description')
                    ->whereNotIsSet()
                    ->whereIsActive()
                    ->get()
                    ->transform(function ($row) {

                        $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;
                        return [
                            'id' => $row->id,
                            'full_description' => $full_description,
                            'description' => $row->description,
                            'currency_type_id' => $row->currency_type_id,
                            'currency_type_symbol' => $row->currency_type->symbol,
                            'sale_unit_price' => $row->sale_unit_price,
                            'purchase_unit_price' => $row->purchase_unit_price,
                            'unit_type_id' => $row->unit_type_id,
                            'category_id' => $row->category_id,
                            'family_id' => $row->family_id,
                            'line_id' => $row->line_id,
                            'brand_id' => $row->brand_id,

                        ];
                    });

                break;
        }

        return $data;
    }
}
