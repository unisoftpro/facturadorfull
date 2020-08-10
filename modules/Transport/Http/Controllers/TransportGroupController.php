<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\TransportGroup;
use Modules\Transport\Http\Resources\TransportGroupCollection;
use Modules\Transport\Http\Requests\TransportGroupRequest;

class TransportGroupController extends Controller
{

    public function index()
    {
        return view('transport::transport-groups.index');
    }


    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = TransportGroup::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new TransportGroupCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = TransportGroup::findOrFail($id);

        return $record;
    }

    public function store(TransportGroupRequest $request)
    {
        $id = $request->input('id');
        $record = TransportGroup::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Grupo editado con éxito':'Grupo registrado con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = TransportGroup::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Grupo eliminado con éxito'
        ];

    }




}
