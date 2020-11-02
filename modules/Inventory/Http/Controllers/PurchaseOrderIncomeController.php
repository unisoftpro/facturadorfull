<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Http\Resources\PurchaseOrderIncomeCollection;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Traits\InventoryTrait;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\PurchaseOrderIncome;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\PurchaseOrderItem;
use Modules\Inventory\Http\Requests\PurchaseOrderIncomeRequest;
use App\Models\Tenant\Company;


class PurchaseOrderIncomeController extends Controller
{

    use InventoryTrait;

    public function index()
    {
        return view('inventory::purchase-order-income.index');
    }

    public function create()
    {
        return view('inventory::purchase-order-income.form');
    }

    public function columns()
    {
        return [
            'date_of_issue' => 'Fecha de emisión',
        ];
    }

    public function records(Request $request)
    {

        $records = PurchaseOrderIncome::where($request->column, 'like', "%{$request->value}%")->latest();

        return new PurchaseOrderIncomeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function tables()
    {
        return [
            'warehouses' => $this->optionsWarehouse(),
            'purchase_orders' => $this->table('purchase_orders')
        ];
    }

    public function table($table)
    {

        $data = [];

        switch ($table) {
            case 'purchase_orders':

                $data = PurchaseOrder::whereConfirmed()->get()->transform(function($row) {
                                        return [
                                            'id' => $row->id,
                                            'number' => $row->number_full,
                                            'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                                            'purchase_order_state_id' => $row->purchase_order_state_id,
                                            'purchase_order_state_description' => $row->purchase_order_state->description,
                                            'supplier_name' => "{$row->supplier->name} - {$row->supplier->number}",
                                            'currency_type_id' => $row->currency_type_id,
                                            'work_order_number' => $row->work_order->number,
                                            'total' => $row->total,
                                            'items' => $row->items->transform(function($item) {
                                                return [
                                                    'id' => $item->id,
                                                    'item_id' => $item->item_id,
                                                    'pending_quantity_income' => (float) $item->pending_quantity_income,
                                                    'internal_id' => optional($item->item)->internal_id,
                                                    'description' => $item->item->description,
                                                    'quantity' =>  (float) $item->quantity,
                                                    'attended_quantity' => (float) $item->attended_quantity,
                                                    'total' => $item->total,
                                                ];
                                            })
                                        ];
                                    });

                break;

        }

        return $data;

    }


    public function store(PurchaseOrderIncomeRequest $request)
    {

        $record = DB::connection('tenant')->transaction(function () use ($request) {

            $purchase_order = $request->purchase_order;
            $company = Company::active();

            $purchase_order_income = PurchaseOrderIncome::create([
                'soap_type_id' => $company->soap_type_id,
                'date_of_issue' => $request->date_of_issue,
                'warehouse_id' => $request->warehouse_id,
                'invoice_description' => $request->invoice_description,
                'number' =>  self::newNumber(),
                'purchase_order_id' => $purchase_order['id']
            ]);

            //income
            foreach ($purchase_order['items'] as $item)
            {

                if($item['attended_quantity'] > 0){

                    $purchase_order_income->inventories()->create([
                        'type' => null,
                        'description' => 'Ingreso desde O. Compra',
                        'item_id' => $item['item_id'],
                        'warehouse_id' => $request->warehouse_id,
                        'warehouse_destination_id' => null,
                        'inventory_transaction_id' => null,
                        'quantity' => $item['attended_quantity'],
                    ]);

                    $p_order_item = PurchaseOrderItem::find($item['id']);

                    if($p_order_item->pending_quantity_income != $p_order_item->quantity){
                        $p_order_item->attended_quantity += $item['attended_quantity'];
                    }else{
                        $p_order_item->attended_quantity = $item['attended_quantity'];
                    }

                    $p_order_item->pending_quantity_income -= $item['attended_quantity'];
                    $p_order_item->save();
                }

            }

            $pending_quantity_po = PurchaseOrderItem::where([['purchase_order_id', $purchase_order['id']], ['pending_quantity_income', '>', 0]])->count();

            if($pending_quantity_po == 0){

                $find_purchase_order = PurchaseOrder::find($purchase_order['id']);
                $find_purchase_order->purchase_order_state_id = '13';
                $find_purchase_order->save();

            }


            return  [
                'success' => true,
                'message' => 'Ingreso creado con éxito'
            ];

        });

        return $record;


    }

    private static function newNumber(){

        $number = PurchaseOrderIncome::select('number')->max('number');
        return ($number) ? (int)$number + 1 : 1;

    }

}
