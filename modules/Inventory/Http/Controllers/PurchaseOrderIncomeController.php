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
use App\Models\Tenant\Item;
use Modules\Inventory\Http\Controllers\WarehouseIncomeController as WarehouseIncomeController;

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
            $attended=[];
            //income
            foreach ($purchase_order['items'] as $item)
            {

                if($item['attended_quantity'] > 0){
                    array_push($attended,['id'=>$item['id'],'quantity'=>$item['attended_quantity']]);
                    /*$purchase_order_income->inventories()->create([
                        'type' => null,
                        'description' => 'Ingreso desde O. Compra',
                        'item_id' => $item['item_id'],
                        'warehouse_id' => $request->warehouse_id,
                        'warehouse_destination_id' => null,
                        'inventory_transaction_id' => null,
                        'quantity' => $item['attended_quantity'],
                    ]);*/

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
            //dd($attended);
            $data = PurchaseOrderIncome::where('id', $purchase_order_income->id)->get()->transform(function($row) {

                return [
                    'warehouse_id' => $row->warehouse_id,
                    'purchase_order_id' => $row->purchase_order_id,
                    'date_of_issue'=>$row->date_of_issue,
                    'supplier_id'=> $row->purchase_order->supplier_id,
                    'warehouse_income_reason_id'=> $row->purchase_order->purchase_order_type_id='01'? '104' : '103',
                    'work_order_id'=>$row->purchase_order->work_order_id,
                    'currency_type_id'=>$row->purchase_order->currency_type_id,
                    'document_reference'=>$row->purchase_order->reference,
                    'cat_document_types_id'=>'01',
                    'observation'=>$row->purchase_order->observation,
                    'reference_date'=>$row->purchase_order->date_of_issue->format('Y-m-d'),
                    'original_total'=>$row->purchase_order->total_value,
                    'exchange_rate_sale'=>$row->purchase_order->exchange_rate_sale,
                    "national_total"=>$row->purchase_order->total_value,
                    'total_value'=>$row->purchase_order->total_value,
                    "total"=>$row->purchase_order->total,
                    'items' => $row->purchase_order->items->transform(function($item) {
                        $CateFami= $this->getItemsPurchase($item->item_id);

                        return [
                            'category_id' => $CateFami[0]->category_id,
                            'family_id' =>  $CateFami[0]->family_id,
                            'discount_four' => 0,
                            'discount_one' => 0,
                            'discount_three' => 0,
                            'discount_two' => 0,
                            'item_id' =>$item->item_id,
                            'last_factor' => "0.00",
                            'last_purchase_price'=> $item->previous_cost,
                            'letter_price'=> null,
                            'list_price'=> $item->unit_price,
                            'num_price'=> 0,
                            'price_fob_alm'=> 0,
                            'price_fob_alm_igv'=> 0,
                            'quantity'=>   $item->quantity,
                            'retail_price'=> 0,
                            'sale_profit_factor'=> 0,
                            'total'=> $item->total,
                            'total_value'=> $item->total_value,
                            'unit_price'=> $item->unit_price,
                            'unit_value'=> $item->unit_value,
                            'warehouse_factor'=>0,
                            'item'=>$item->itemss,
                        ];
                    })
                ];
            });
            //$new->sotore2($data);

            return  [
                'success' => true,
                'message' => 'Ingreso creado con éxito',
                'data'=>$data
            ];

        });

        return $record;


    }
    public function getItemsPurchase($id){
        $data = Item::where('id',$id)->select('category_id','family_id')->get();
        return $data;
    }
    private static function newNumber(){

        $number = PurchaseOrderIncome::select('number')->max('number');
        return ($number) ? (int)$number + 1 : 1;

    }

}
