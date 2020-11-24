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
use Modules\Purchase\Models\PurchaseOrderItem;

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
            ->select(
                'warehouse_income_items.item_id',
                'items.description',
                'items.item_code',
                'warehouse_income.warehouse_id',
                'items.name',
                'families.name as descriptionf',
                'lines.name AS descriptionl',
                DB::raw('sum(warehouse_income_items.quantity) as ingresos'),
                'families.id as idF',
                'lines.id as idL'
            )
            ->where($wheres)
            ->groupBy('warehouse_income_items.item_id', 'items.description', 'items.item_code', 'warehouse_income.warehouse_id', 'items.name', 'families.name', 'lines.name', 'families.id', 'lines.id');

        return new ReportValutedBalancesCollection($ingreso->paginate(config('tenant.items_per_page')));
    }
    public function pdf(Request $request)
    {
        //dd($request);
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
        $warehouse = Warehouse::where('id', $request->warehouse_id)->first();
        $itemsgroup = [];
        $ingreso = WarehouseIncome::join('warehouse_income_items', 'warehouse_income.id', '=', 'warehouse_income_items.warehouse_income_id')
            ->join('items', 'items.id', '=', 'warehouse_income_items.item_id')
            ->join('families', 'families.id', 'items.family_id')
            ->join('lines', 'lines.id', '=', 'items.line_id')
            ->select(
                'warehouse_income_items.item_id',
                'items.description',
                'items.item_code',
                'warehouse_income.warehouse_id',
                'items.name',
                'families.name as descriptionf',
                'lines.name AS descriptionl',
                DB::raw('sum(warehouse_income_items.quantity) as ingresos'),
                'families.id as idF',
                'lines.id as idL'
            )
            ->where($wheres)
            ->groupBy('warehouse_income_items.item_id', 'items.description', 'items.item_code', 'warehouse_income.warehouse_id', 'items.name', 'families.name', 'lines.name', 'families.id', 'lines.id')->get();

        for ($i = 0; $i < count($ingreso); $i++) {
            $purchase_order_item = PurchaseOrderItem::where('item_id', $ingreso[$i]['item_id'])->latest('id')->first();
            $salida = WarehouseExpense::join('warehouse_expense_items', 'warehouse_expense.id', '=', 'warehouse_expense_items.warehouse_expense_id')
                ->join('items', 'items.id', '=', 'warehouse_expense_items.item_id')
                ->join('families', 'families.id', 'items.family_id')
                ->join('lines', 'lines.id', '=', 'items.line_id')
                ->select(
                    DB::raw('sum(warehouse_expense_items.quantity) as salidas')
                )
                ->where('warehouse_expense_items.item_id', $ingreso[$i]['item_id'])
                ->where('warehouse_expense.warehouse_expense_id', $ingreso[$i]['warehouse_id'])
                ->where('families.id', $ingreso[$i]['idF'])
                ->where('lines.id', $ingreso[$i]['idL'])
                ->groupBy('warehouse_expense_items.item_id', 'warehouse_expense.warehouse_expense_id', 'items.name', 'families.name', 'lines.name', 'families.id', 'lines.id')->get();
            if (count($salida) > 0) {
                $saldo = (int) $ingreso[$i]['ingresos'] - (int)  $salida[0]['salidas'];
            } else {
                $saldo = (int) $ingreso[$i]['ingresos'];
            }
            array_push($itemsgroup, ['item_code' => $ingreso[$i]['item_code'], 'description' => $ingreso[$i]['description'], 'warehouse_id' => $ingreso[$i]['warehouse_id'], 'name' => $ingreso[$i]['name'], 'descriptionf' => $ingreso[$i]['descriptionf'], 'descriptionl' => $ingreso[$i]['descriptionl'], 'saldos' => $saldo, 'idF' => $ingreso[$i]['idF'], 'idL' => $ingreso[$i]['idL'], 'price_fob' => round($purchase_order_item->unit_price, 3), 'Totalart' => $saldo * round($purchase_order_item->unit_price, 3), 'currency_type_id' => $purchase_order_item->purchase_order->currency_type_id]);
        }
        $view = "inventory::reports.valuted-balances.pdf";

        set_time_limit(0);
        $igv = $request->price_default;

        $pdf = PDF::loadView($view, compact("itemsgroup", "warehouse"));
        $filename = "Reporte_Saldo_Valorizado";

        return $pdf->download($filename . '.pdf');
    }
}
