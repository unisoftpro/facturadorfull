<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Item\Models\Line;
use Modules\Item\Http\Resources\LineCollection;
use Modules\Item\Http\Requests\LineRequest;

class LineController extends Controller
{

    public function index()
    {
        return view('item::lines.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = Line::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new LineCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Line::findOrFail($id);

        return $record;
    }

    public function store(LineRequest $request)
    {
        $id = $request->input('id');
        $record = Line::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Línea editada con éxito':'Línea registrada con éxito',
            'data' => $record
        ];

    }

    public function destroy($id)
    {
        try {

            $record = Line::findOrFail($id);
            $record->delete();

            return [
                'success' => true,
                'message' => 'Línea eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "La Línea esta siendo usada por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar la Línea"];

        }

    }




}
