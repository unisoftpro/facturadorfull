<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Item\Models\Family;
use Modules\Item\Models\Line;
use App\Models\Tenant\Warehouse;
use App\Models\Tenant\Catalogs\CurrencyType;
use Illuminate\Http\Request;
use App\Models\Tenant\ItemWarehouse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Inventory\Http\Resources\ReportValutedBalancesCollection;

class ReportValutedBalancesController extends Controller
{
    public function index()
    {
        return view('inventory::reports.valuted-balances.index');
    }
    public function filters()
    {
        return [
            'families' => Family::all(),
            'lines' => Line::all(),
            'warehouse' => Warehouse::all(),
            'cat_currency_types' => CurrencyType::all()
        ];
    }
    public function records(Request $request)
    {

        // [['item_type_id', '01'], ['unit_type_id', '!=','ZZ']]
        $wheres = [];
        if ($request->families_id) :
            $array1 = ['items.family_id', $request->families_id];
            array_push($wheres, $array1);
        endif;
        if ($request->lines_id) :
            $array1 = ['items.line_id', $request->lines_id];
            array_push($wheres, $array1);
        endif;
        if ($request->warehouse_id) :
            $array1 = ['item_warehouse.warehouse_id', $request->warehouse_id];
            array_push($wheres, $array1);
        endif;
        if( $request->currencytype_id):
            $array1 = ['items.currency_type_id', $request->currencytype_id];
            array_push($wheres, $array1);
        endif;

        $record = ItemWarehouse::join('list_price_item', 'item_warehouse.item_id', '=', 'list_price_item.item_id')
            ->join('items', 'items.id', '=', 'list_price_item.item_id')
            ->join('families', 'families.id', 'items.family_id')
            ->join('lines', 'lines.id', '=', 'items.line_id')
            ->select(
                'items.id',
                'items.name AS description',
                'families.name as descriptionf',
                'lines.name AS descriptionl',
                'items.item_code',
                'list_price_item.price_fob',
                'items.currency_type_id',
                'items.internal_id',
                DB::raw('round((sum(item_warehouse.stock)*list_price_item.price_fob),2) as Totalart'),
                DB::raw('sum(item_warehouse.stock) as stock')
            )
            ->where($wheres)
            ->groupBy('items.id', 'items.name', 'families.name', 'lines.name', 'items.item_code', 'list_price_item.price_fob','items.currency_type_id','items.internal_id');




        //$lista_precios = ListPriceItem::where()->select()->get();


        //$records = $this->getRecords($request->all());
        //dd($record);

        return new ReportValutedBalancesCollection($record->paginate(config('tenant.items_per_page')));
    }
}
