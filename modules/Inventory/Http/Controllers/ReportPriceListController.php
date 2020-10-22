<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\TypeListPrice;
use Modules\Item\Models\Brand;
use Modules\Item\Models\Family;
use Modules\Item\Models\Line;
use App\Models\Tenant\Warehouse;
use Illuminate\Http\Request;
use App\Models\Tenant\ItemWarehouse;
use Modules\Inventory\Http\Resources\ReportPrecieListCollection;
use Barryvdh\DomPDF\Facade as PDF;

class ReportPriceListController extends Controller
{
    public function index()
    {
        return view('inventory::reports.precie-list.index');
    }
    public function filters()
    {
        return [
            'type_list_prices' => TypeListPrice::all(),
            'brands' => Brand::all(),
            'families' => Family::all(),
            'lines' => Line::all(),
            'warehouse' => Warehouse::all()

        ];
    }
    public function records(Request $request)
    {

        // [['item_type_id', '01'], ['unit_type_id', '!=','ZZ']]
        $wheres = [];
        if ($request->list_type_id) :
            $array1 = ['list_price_item.list_type_id', $request->list_type_id];
            array_push($wheres, $array1);
        endif;
        if ($request->families_id) :
            $array1 = ['items.family_id', $request->families_id];
            array_push($wheres, $array1);
        endif;
        if ($request->brands_id) :
            $array1 = ['items.brand_id', $request->brands_id];
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

        if ($request->price_default === "2") {
            $record = ItemWarehouse::join('list_price_item', 'item_warehouse.item_id', '=', 'list_price_item.item_id')
                ->join('items', 'items.id', '=', 'list_price_item.item_id')
                ->join('families', 'families.id', 'items.family_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('lines', 'lines.id', '=', 'items.line_id')
                ->select(
                    'items.id',
                    'items.name AS description',
                    'families.name as descriptionf',
                    'brands.name AS descripctionb',
                    'lines.name AS descriptionl',
                    'items.item_code',
                    'items.unit_type_id',
                    'list_price_item.price_list',
                    DB::raw('sum(item_warehouse.stock) as stock')
                )
                ->where($wheres)
                ->groupBy('items.id', 'items.name', 'families.name', 'brands.name', 'lines.name', 'items.item_code', 'items.unit_type_id', 'list_price_item.price_list');
        } else {
            $record = ItemWarehouse::join('list_price_item', 'item_warehouse.item_id', '=', 'list_price_item.item_id')
                ->join('items', 'items.id', '=', 'list_price_item.item_id')
                ->join('families', 'families.id', 'items.family_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('lines', 'lines.id', '=', 'items.line_id')
                ->select(
                    'items.id',
                    'items.name AS description',
                    'families.name as descriptionf',
                    'brands.name AS descripctionb',
                    'lines.name AS descriptionl',
                    'items.item_code',
                    'items.unit_type_id',
                    DB::raw('round((list_price_item.price_list * 1.18),2) as price_list'),
                    DB::raw('sum(item_warehouse.stock) as stock')
                )
                ->where($wheres)
                ->groupBy('items.id', 'items.name', 'families.name', 'brands.name', 'lines.name', 'items.item_code', 'items.unit_type_id', 'list_price_item.price_list');
        }



        //$lista_precios = ListPriceItem::where()->select()->get();


        //$records = $this->getRecords($request->all());
        //dd($record);

        return new ReportPrecieListCollection($record->paginate(config('tenant.items_per_page')));
    }
    public function pdf(Request $request)
    {

        $wheres = [];
        if ($request->list_type_id) :
            $array1 = ['list_price_item.list_type_id', $request->list_type_id];
            array_push($wheres, $array1);
        endif;
        if ($request->families_id) :
            $array1 = ['items.family_id', $request->families_id];
            array_push($wheres, $array1);
        endif;
        if ($request->brands_id) :
            $array1 = ['items.brand_id', $request->brands_id];
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

        if ($request->price_default === "2") {
            $record = ItemWarehouse::join('list_price_item', 'item_warehouse.item_id', '=', 'list_price_item.item_id')
                ->join('items', 'items.id', '=', 'list_price_item.item_id')
                ->join('families', 'families.id', 'items.family_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('lines', 'lines.id', '=', 'items.line_id')
                ->select(
                    'items.id',
                    'items.name AS description',
                    'families.name as descriptionf',
                    'brands.name AS descripctionb',
                    'lines.name AS descriptionl',
                    'items.item_code',
                    'items.unit_type_id',
                    'list_price_item.price_list',
                    DB::raw('sum(item_warehouse.stock) as stock')
                )
                ->where($wheres)
                ->groupBy('items.id', 'items.name', 'families.name', 'brands.name', 'lines.name', 'items.item_code', 'items.unit_type_id', 'list_price_item.price_list');
        } else {
            $record = ItemWarehouse::join('list_price_item', 'item_warehouse.item_id', '=', 'list_price_item.item_id')
                ->join('items', 'items.id', '=', 'list_price_item.item_id')
                ->join('families', 'families.id', 'items.family_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('lines', 'lines.id', '=', 'items.line_id')
                ->select(
                    'items.id',
                    'items.name AS description',
                    'families.name as descriptionf',
                    'brands.name AS descripctionb',
                    'lines.name AS descriptionl',
                    'items.item_code',
                    'items.unit_type_id',
                    DB::raw('round((list_price_item.price_list * 1.18),2) as price_list'),
                    DB::raw('sum(item_warehouse.stock) as stock')
                )
                ->where($wheres)
                ->groupBy('items.id', 'items.name', 'families.name', 'brands.name', 'lines.name', 'items.item_code', 'items.unit_type_id', 'list_price_item.price_list');
        }
        $view = "inventory::reports.precie-list.format";

        set_time_limit(0);
        $igv = $request->price_default;
        $pdf = PDF::loadView($view, compact("record", "igv"));
        $filename = "Reporte_Salida";

        return $pdf->download($filename . '.pdf');
    }
}
