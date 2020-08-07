<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Vehicles\Insurance;
use Modules\Transport\Http\Resources\InsuranceCollection;
use Modules\Transport\Http\Requests\InsuranceRequest;

class InsuranceController extends Controller
{

    public function index()
    {
        return view('transport::insurance.index');
    }


    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = Insurance::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new InsuranceCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Insurance::findOrFail($id);

        return $record;
    }

    public function store(InsuranceRequest $request)
    {
        $id = $request->input('id');
        $record = Insurance::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Seguro editado con éxito':'Seguro registrado con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = Insurance::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Seguro eliminado con éxito'
        ];

    }




}
