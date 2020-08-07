<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Vehicles\Color;
use Modules\Transport\Http\Resources\ColorCollection;
use Modules\Transport\Http\Requests\ColorRequest;

class ColorController extends Controller
{

    public function index()
    {
        return view('transport::colors.index');
    }


    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = Color::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ColorCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Color::findOrFail($id);

        return $record;
    }

    public function store(ColorRequest $request)
    {
        $id = $request->input('id');
        $record = Color::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Color editado con éxito':'Color registrado con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = Color::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Color eliminado con éxito'
        ];

    }




}
