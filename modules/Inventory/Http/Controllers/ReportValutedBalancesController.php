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
use Modules\Inventory\Models\WarehouseExpense;
use Modules\Inventory\Models\WarehouseIncome;

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
        /*if ($request->families_id) :
            $array1 = ['items.family_id', $request->families_id];
            array_push($wheres, $array1);
        endif;
        if ($request->lines_id) :
            $array1 = ['items.line_id', $request->lines_id];
            array_push($wheres, $array1);
        endif;*/
        if ($request->warehouse_id) :
            $array1 = ['warehouse_income.warehouse_id', $request->warehouse_id];
            array_push($wheres, $array1);
        endif;
        /*if ($request->currencytype_id) :
            $array1 = ['items.currency_type_id', $request->currencytype_id];
            array_push($wheres, $array1);
        endif;*/
        $ingreso = WarehouseIncome::join('warehouse_income_items', 'warehouse_income.id', '=', 'warehouse_income_items.warehouse_income_id')
            ->join('items', 'items.id', '=', 'warehouse_income_items.item_id')
            ->join('families', 'families.id', 'items.family_id')
            ->join('lines', 'lines.id', '=', 'items.line_id')
            ->select('warehouse_income_items.item_id', 'items.description','items.item_code','warehouse_income.warehouse_id', 'items.name', 'families.name as descriptionf', 'lines.name AS descriptionl',
            DB::raw('sum(warehouse_income_items.quantity) as ingresos'),'families.id as idF','lines.id as idL')
            ->where($wheres)
            ->groupBy('warehouse_income_items.item_id', 'items.description','items.item_code','warehouse_income.warehouse_id', 'items.name', 'families.name', 'lines.name','families.id','lines.id');

        return new ReportValutedBalancesCollection($ingreso->paginate(config('tenant.items_per_page')));
    }
}
