<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Vehicles\Fuel;
use Modules\Transport\Models\Vehicles\FuelType;
use Modules\Transport\Http\Resources\FuelCollection;
use Modules\Transport\Http\Requests\FuelRequest;

class FuelController extends Controller
{

    public function index()
    {
        return view('transport::fuels.index');
    }


    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = Fuel::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new FuelCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {
        $fuel_types = FuelType::get();

        return compact('fuel_types');
    }

    public function record($id)
    {
        $record = Fuel::findOrFail($id);

        return $record;
    }

    public function store(FuelRequest $request)
    {
        $id = $request->input('id');
        $record = Fuel::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Combustible editado con éxito':'Combustible registrado con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = Fuel::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Combustible eliminado con éxito'
        ];

    }




}
