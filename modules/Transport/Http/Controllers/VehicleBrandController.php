<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Vehicles\VehicleBrand;
use Modules\Transport\Http\Resources\VehicleBrandCollection;
use Modules\Transport\Http\Requests\VehicleBrandRequest;

class VehicleBrandController extends Controller
{

    public function index()
    {
        return view('transport::vehicle-brands.index');
    }


    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = VehicleBrand::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new VehicleBrandCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = VehicleBrand::findOrFail($id);

        return $record;
    }

    public function store(VehicleBrandRequest $request)
    {
        $id = $request->input('id');
        $record = VehicleBrand::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Marca editada con éxito':'Marca registrada con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = VehicleBrand::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Marca eliminada con éxito'
        ];

    }




}
