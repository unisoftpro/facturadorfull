<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Item\Models\Family;
use Modules\Item\Http\Resources\FamilyCollection;
use Modules\Item\Http\Requests\FamilyRequest;

class FamilyController extends Controller
{

    public function index()
    {
        return view('item::families.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = Family::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new FamilyCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Family::findOrFail($id);

        return $record;
    }

    public function store(FamilyRequest $request)
    {
        $id = $request->input('id');
        $record = Family::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Familia editada con éxito':'Familia registrada con éxito',
            'data' => $record
        ];

    }

    public function destroy($id)
    {
        try {

            $record = Family::findOrFail($id);
            $record->delete();

            return [
                'success' => true,
                'message' => 'Familia eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "La Familia esta siendo usada por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar la Familia"];

        }

    }




}
