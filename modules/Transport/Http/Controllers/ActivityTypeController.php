<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\ActivityType;
use Modules\Transport\Http\Resources\ActivityTypeCollection;
use Modules\Transport\Http\Requests\ActivityTypeRequest;

class ActivityTypeController extends Controller
{

    public function index()
    {
        return view('transport::activity-types.index');
    }


    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = ActivityType::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ActivityTypeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = ActivityType::findOrFail($id);

        return $record;
    }

    public function store(ActivityTypeRequest $request)
    {
        $id = $request->input('id');
        $record = ActivityType::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Tipo de actividad editada con éxito':'Tipo de actividad registrada con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = ActivityType::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Tipo de actividad eliminada con éxito'
        ];

    }




}
