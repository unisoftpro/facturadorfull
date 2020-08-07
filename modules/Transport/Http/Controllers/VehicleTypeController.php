<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Vehicles\VehicleType;
use Modules\Transport\Http\Resources\VehicleTypeCollection;
use Modules\Transport\Http\Requests\VehicleTypeRequest;

class VehicleTypeController extends Controller
{

    public function index()
    {
        return view('transport::vehicle-types.index');
    }


    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = VehicleType::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new VehicleTypeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = VehicleType::findOrFail($id);

        return $record;
    }

    public function store(VehicleTypeRequest $request)
    {
        $id = $request->input('id');
        $record = VehicleType::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Tipo de vehículo editado con éxito':'Tipo de vehículo registrado con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = VehicleType::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Tipo de vehículo eliminado con éxito'
        ];

    }




}
