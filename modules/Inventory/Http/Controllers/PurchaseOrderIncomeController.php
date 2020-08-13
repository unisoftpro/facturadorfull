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
use Modules\Inventory\Http\Requests\PurchaseOrderIncomeRequest;


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
            //'items' => $this->optionsItemWareHouse(),
            'warehouses' => $this->optionsWarehouse()
        ];
    }

    public function record($id)
    {
        $record = new TransferResource(Inventory::findOrFail($id));

        return $record;
    }

 
    public function stock ($item_id, $warehouse_id)
    {

       $row = ItemWarehouse::where([['item_id', $item_id],['warehouse_id', $warehouse_id]])->first();

       return [
           'stock' => ($row) ? $row->stock : 0
       ];

    }

    public function store(PurchaseOrderIncomeRequest $request)
    {

        $result = DB::connection('tenant')->transaction(function () use ($request) {

            $row = InventoryTransfer::create([
                'description' => $request->description,
                'warehouse_id' => $request->warehouse_id,
                'warehouse_destination_id' => $request->warehouse_destination_id,
                'quantity' =>  count( $request->items ),
            ]);

            foreach ($request->items as $it)
            {
                $inventory = new Inventory();
                $inventory->type = 2;
                $inventory->description = 'Traslado';
                $inventory->item_id = $it['id'];
                $inventory->warehouse_id = $request->warehouse_id;
                $inventory->warehouse_destination_id = $request->warehouse_destination_id;
                $inventory->quantity = $it['quantity'];
                $inventory->inventories_transfer_id = $row->id;

                $inventory->save();

                foreach ($it['lots'] as $lot){

                    if($lot['has_sale']){
                        $item_lot = ItemLot::findOrFail($lot['id']);
                        $item_lot->warehouse_id = $inventory->warehouse_destination_id;
                        $item_lot->update();
                    }

                }
            }

            return  [
                'success' => true,
                'message' => 'Traslado creado con éxito'
            ];
        });

        return $result;


    }


    public function items($warehouse_id)
    {
        return [
            'items' => $this->optionsItemWareHousexId($warehouse_id),
        ];
    }








}
