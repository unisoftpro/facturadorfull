<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Http\Resources\WarehouseIncomeResource;
use Modules\Inventory\Http\Resources\WarehouseIncomeCollection;
use Modules\Inventory\Models\WarehouseIncome;
use Modules\Inventory\Models\WarehouseIncomeItem;
use Modules\Inventory\Models\WarehouseIncomeReason;
use App\Models\Tenant\Catalogs\DocumentType;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\PurchaseOrderItem;
use Modules\Inventory\Http\Requests\WarehouseIncomeRequest;
use App\Models\Tenant\Company;
use Modules\Transport\Models\WorkOrder;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\Models\Tenant\Person;
use App\Http\Controllers\Tenant\Api\ServiceController;
use Illuminate\Support\Str;
use App\Models\Tenant\Item;
use Modules\Inventory\Traits\{
    InventoryTrait,
    UtilityTrait,
};
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Inventory\Models\Warehouse as ModuleWarehouse;
use App\Models\Tenant\TypeListPrice;
use Modules\Inventory\Http\Resources\WareHouseIncomeCollection2;

class WarehouseIncomeController extends Controller
{

    use InventoryTrait, UtilityTrait;

    protected $warehouse_income;


    public function index()
    {
        return view('inventory::warehouse-income.index');
    }

    public function create($id = null)
    {
        return view('inventory::warehouse-income.form', compact('id'));
    }

    public function columns()
    {
        return [
            'date_of_issue' => 'Fecha',
        ];
    }

    public function records(Request $request)
    {
        $records = WarehouseIncome::where($request->column, 'like', "%{$request->value}%")->latest();


        return new WarehouseIncomeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = WarehouseIncome::findOrFail($id);

        return new WarehouseIncomeResource($record);
    }
    public function record_warehouse($id)
    {
        $record = WarehouseIncome::findOrFail($id);
        //dd( $record );
        return new WareHouseIncomeCollection2($record);
    }


    public function tables()
    {

        return [
            'warehouses' => $this->optionsWarehouse(),
            'purchase_orders' => $this->table('purchase_orders'),
            'warehouse_income_reasons' => WarehouseIncomeReason::get(),
            'suppliers' => $this->table('suppliers'),
            'currency_types' => CurrencyType::whereActive()->get(),
            'work_orders' => WorkOrder::get(),
            'type_list_prices' => TypeListPrice::all(),
            'document_types_invoice' => DocumentType::all(),
        ];
    }


    public function item_tables()
    {

        $items = $this->table('items');

        return compact('items');
    }


    public function getListPrice($item_id, $purchase_order_id)
    {

        $item = PurchaseOrderItem::where([['purchase_order_id', $purchase_order_id], ['item_id', $item_id]])->first();

        if ($item) {
            return [
                'list_price' => round($item->unit_value, 2)
            ];
        }

        return [
            'list_price' => 0
        ];
    }


    public function getExchangeRate($date_reference, $supplier_id)
    {

        $record = PurchaseOrder::where([['date_of_issue', $date_reference], ['supplier_id', $supplier_id]])->first();

        if ($record) {
            return [
                'success' => true,
                'message' => '',
                'exchange_rate_sale' => $record->exchange_rate_sale
            ];
        }

        $exchange_rate = app(ServiceController::class)->exchangeRateTest(date('Y-m-d'));

        return [
            'success' => false,
            'message' => 'No se encontró una O. Compra asociada al proveedor, se obtendra el T/C del día',
            'exchange_rate_sale' => (array_key_exists('sale', $exchange_rate)) ? $exchange_rate['sale'] : 1
        ];
    }
    public function getPurcharse($supplier_id)
    {
        $record = PurchaseOrder::where('supplier_id', $supplier_id)->where('purchase_order_state_id', '03')->get();
        return compact('record');
    }
    public function getPurcharseId($supplier_id,$id){
        $record = PurchaseOrder::where('supplier_id', $supplier_id)->where('id', $id)->get();
        return compact('record');
    }
    public function getWorkOrder($purchase_order_id)
    {
        $record = PurchaseOrder::where('id', $purchase_order_id)->select('work_order_id')->first();
        $work_order = WorkOrder::where('id', $record->work_order_id)->get();
        return compact('work_order');
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
    public function getItemsWareHouseIncome($id)
    {

        //dd($id);
        $record = WarehouseIncomeItem::where('warehouse_income_id', $id)->get();

        return compact('record');
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


    public function store(WarehouseIncomeRequest $request)
    {


        $record = DB::connection('tenant')->transaction(function () use ($request) {

            $data = $this->mergeData($request);

            $this->warehouse_income = WarehouseIncome::updateOrCreate(['id' => (int)$data['id']],$data);
           /* for ($i=0; $i < count($data['items']) ; $i++) {
                $this->warehouse_income->items()->updateOrCreate(['warehouse_income_id' => $data['id']],$row);
            }*/
            //dd($data['items']);
            foreach ($data['items'] as $row) {

                $this->warehouse_income->items()->updateOrCreate(['warehouse_income_id' =>(int)$data['id'],'item_id'=>(int)$row['item_id']],$row);

            }
            if ($data['purchase_order_id'] != null && $data['id']==null) {
                foreach ($data['items'] as $item) {

                    if ($item['attended_quantity'] > 0) {

                        /*$purchase_order_income->inventories()->create([
                            'type' => null,
                            'description' => 'Ingreso desde O. Compra',
                            'item_id' => $item['item_id'],
                            'warehouse_id' => $request->warehouse_id,
                            'warehouse_destination_id' => null,
                            'inventory_transaction_id' => null,
                            'quantity' => $item['attended_quantity'],
                        ]);*/
                        //dd($item['attended_quantity']);
                        $p_order_item = PurchaseOrderItem::where([['purchase_order_id', $data['purchase_order_id']], ['item_id', $item['item_id']]])->get();
                        //dd($p_order_item[0]['pending_quantity_income']);
                        if ((int)$p_order_item[0]['pending_quantity_income'] != (int) $p_order_item[0]['quantity']) {
                            (int) $p_order_item[0]['attended_quantity'] += (int) $item['attended_quantity'];
                        } else {
                            (int) $p_order_item[0]['attended_quantity'] = (int) $item['attended_quantity'];
                        }

                        (int) $p_order_item[0]['pending_quantity_income'] -= (int) $item['attended_quantity'];
                        //dd((int) $p_order_item[0]['attended_quantity'],(int) $p_order_item[0]['pending_quantity_income']);
                        PurchaseOrderItem::where([['purchase_order_id', $data['purchase_order_id']], ['item_id', $item['item_id']]])->update(['attended_quantity' => (int) $p_order_item[0]['attended_quantity'], 'pending_quantity_income' => (int) $p_order_item[0]['pending_quantity_income']]);
                    }
                }

                $pending_quantity_po = PurchaseOrderItem::where([['purchase_order_id', $data['purchase_order_id']], ['pending_quantity_income', '>', 0]])->count();

                if ($pending_quantity_po == 0) {

                    $find_purchase_order = PurchaseOrder::find($data['purchase_order_id']);
                    $find_purchase_order->purchase_order_state_id = '13';
                    $find_purchase_order->save();
                }
            } else {

            }


            $this->setFilename($this->warehouse_income);
            $this->createPdf($this->warehouse_income, "a4", 'warehouse_income');
        });

        return  [
            'success' => true,
            'message' => 'Ingreso creado con éxito',
            'data' => [
                'id' => $this->warehouse_income->id,
            ],
        ];

        //return $record;

    }


    public function mergeData($inputs)
    {

        $company = Company::active();

        $values = [
            'user_id' => auth()->id(),
            'soap_type_id' => $company->soap_type_id,
            'supplier' => PersonInput::set($inputs['supplier_id']),
            'external_id' => Str::uuid()->toString(),
            'number' =>  $this->newNumber(WarehouseIncome::class),
        ];

        $inputs->merge($values);

        return $inputs->all();
    }

    public function download($external_id, $template)
    {
        // $establishment_id = auth()->user()->establishment_id;
        // $warehouse = ModuleWarehouse::where('establishment_id', $establishment_id)->first();
        $company = Company::first();

        $record = WarehouseIncome::where('external_id', $external_id)->first();
        $view = "inventory::warehouse-income.report.{$template}";
        //dd($record);
        set_time_limit(0);

        $pdf = PDF::loadView($view, compact("record", "company"));
        $filename = "Reporte_{$template}";

        return $pdf->download($filename . '.pdf');
    }
    public function destroy($id)
    {

        DB::connection('tenant')->transaction(function () use ($id) {

            $record = WarehouseIncome::findOrFail($id);

            $items_ingresos = $record->items;
            //dd($record,$items_ingresos);
            for ($i=0; $i < count($items_ingresos) ; $i++) {

                $purcharse_order = PurchaseOrderItem::where([['purchase_order_id',$record->purchase_order_id],['item_id',$items_ingresos[$i]['item_id']]])->first();
                $purcharse_order->pending_quantity_income += $items_ingresos[$i]['quantity'];
                $purcharse_order->attended_quantity -= $items_ingresos[$i]['quantity'];
                $purcharse_order->update();

            }
            $record->items()->delete();
            $record->delete();
            /*$origin_inv_kardex = $record->inventory_kardex->first();
            $destination_inv_kardex = $record->inventory_kardex->last();

            $destination_item_warehouse = ItemWarehouse::where([['item_id',$destination_inv_kardex->item_id],['warehouse_id', $destination_inv_kardex->warehouse_id]])->first();
            $destination_item_warehouse->stock -= $record->quantity;
            $destination_item_warehouse->update();

            $origin_item_warehouse = ItemWarehouse::where([['item_id',$origin_inv_kardex->item_id],['warehouse_id', $origin_inv_kardex->warehouse_id]])->first();
            $origin_item_warehouse->stock += $record->quantity;
            $origin_item_warehouse->update();

            $record->inventory_kardex()->delete();
            $record->delete();*/

        });


        return [
            'success' => true,
            'message' => 'Ingreso eliminado con éxito'
        ];



    }
}
