<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\ServiceType;
use Modules\Transport\Http\Resources\ServiceTypeCollection;
use Modules\Transport\Http\Requests\ServiceTypeRequest;

class ServiceTypeController extends Controller
{

    public function index()
    {
        return view('transport::service-types.index');
    }


    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = ServiceType::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ServiceTypeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = ServiceType::findOrFail($id);

        return $record;
    }

    public function store(ServiceTypeRequest $request)
    {
        $id = $request->input('id');
        $record = ServiceType::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Tipo de servicio editado con éxito':'Tipo de servicio registrado con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = ServiceType::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Tipo de servicio eliminado con éxito'
        ];

    }




}
