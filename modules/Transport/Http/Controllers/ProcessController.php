<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Process;
use Modules\Transport\Http\Resources\ProcessCollection;
use Modules\Transport\Http\Requests\ProcessRequest;

class ProcessController extends Controller
{

    public function index()
    {
        return view('transport::processes.index');
    }


    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = Process::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ProcessCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Process::findOrFail($id);

        return $record;
    }

    public function store(ProcessRequest $request)
    {
        $id = $request->input('id');
        $record = Process::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Proceso editado con éxito':'Proceso registrado con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = Process::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Proceso eliminado con éxito'
        ];

    }




}
