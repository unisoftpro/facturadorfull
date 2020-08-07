<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Vehicles\FuelType;
use Modules\Transport\Http\Resources\FuelTypeCollection;
use Modules\Transport\Http\Requests\FuelTypeRequest;

class FuelTypeController extends Controller
{

    public function index()
    {
        return view('transport::fuel-types.index');
    }


    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = FuelType::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new FuelTypeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = FuelType::findOrFail($id);

        return $record;
    }

    public function store(FuelTypeRequest $request)
    {
        $id = $request->input('id');
        $record = FuelType::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Tipo de combustible editado con éxito':'Tipo de combustible registrado con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = FuelType::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Tipo de combustible eliminado con éxito'
        ];

    }




}
