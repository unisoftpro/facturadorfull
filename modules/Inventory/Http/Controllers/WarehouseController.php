<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\InventoryKardex;
use Illuminate\Http\Request;
use Modules\Inventory\Http\Resources\WarehouseCollection;
use Modules\Inventory\Http\Resources\WarehouseResource;
use Modules\Inventory\Http\Requests\WarehouseRequest;
use Modules\Inventory\Http\Resources\WareIncomenCollection;
use Modules\Inventory\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        return view('inventory::warehouses.index');
    }

    public function columns()
    {
        return [
            'description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request->all());

        /*$records = Warehouse::where($request->column, 'like', "%{$request->value}%")
                            ->orderBy('description');

        return new WarehouseCollection($records->paginate(config('tenant.items_per_page')));*/
        return new WareIncomenCollection($records->paginate(config('tenant.items_per_page')));
    }
    public function getRecords($request){

        $item_id = $request['item_id'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];

        $records = $this->data($item_id, $date_start, $date_end);

        return $records;

    }
    private function data($item_id, $date_start, $date_end)
    {

        $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

        if($date_start && $date_end){

            $data = InventoryKardex::with(['inventory_kardexable'])
                        //->where([['warehouse_id', $warehouse->id]])
                        ->whereBetween('date_of_issue', [$date_start, $date_end])
                        ->orderBy('item_id')->orderBy('id');

        }else{

            $data = InventoryKardex::with(['inventory_kardexable'])
                        //->where([['warehouse_id', $warehouse->id]])
                        ->orderBy('item_id')->orderBy('id');
        }

        if($item_id){
            $data = $data->where('item_id', $item_id);
        }
        return $data;

    }

    public function record($id)
    {
        $record = new WarehouseResource(Warehouse::findOrFail($id));

        return $record;
    }

    public function store(WarehouseRequest $request)
    {
        $id = $request->input('id');
        if(!$id) {
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
            if($warehouse) {
                return [
                    'success' => false,
                    'message' => 'Solo es posible registrar un almacén por establecimiento.'
                ];
            }
        }

        $record = Warehouse::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        if(!$id) {
            $record->establishment_id = auth()->user()->establishment_id;
        }
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Almacén editado con éxito':'Almacén registrado con éxito',
            'id' => $record->id
        ];
    }
}
