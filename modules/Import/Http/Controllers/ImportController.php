<?php

namespace Modules\Import\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Configuration;
use Modules\Import\Models\ImportDocument;
use Modules\Import\Http\Resources\ImportDocumentCollection;
use App\Imports\CustomDocumentsImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class ImportController extends Controller
{
     
    public function index()
    {
        if(!Configuration::first()->import_documents)
            return redirect()->route('tenant.documents.index');

        return view('import::documents.index');
    }
 
    public function columns()
    {
        return [
            'id' => 'Identificador',
        ];
    }

    public function records(Request $request)
    {
        $records = ImportDocument::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ImportDocumentCollection($records->paginate(config('tenant.items_per_page')));
    }


    
    public function import(Request $request)
    {


        if ($request->hasFile('file')) {
            try {
                $import = new CustomDocumentsImport();
                $import->import($request->file('file'), null, Excel::XLSX);
                $data = $import->getData();
                return [
                    'success' => true,
                    'message' =>  __('app.actions.upload.success'),
                    'data' => $data
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' =>  $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }




 



    // public function store(ExpenseRequest $request)
    // {
    //     $data = self::merge_inputs($request);

    //     $expense = DB::connection('tenant')->transaction(function () use ($data) {

    //         $doc = Expense::create($data);
    //         foreach ($data['items'] as $row)
    //         {
    //             $doc->items()->create($row);
    //         } 

    //         foreach ($data['payments'] as $row)
    //         {
    //             $doc->payments()->create($row);
    //         }             

    //         return $doc;
    //     });       
 
    //     return [
    //         'success' => true,
    //         'data' => [
    //             'id' => $expense->id,
    //         ],
    //     ];
    // }

}
