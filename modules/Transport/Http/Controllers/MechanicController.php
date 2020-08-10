<?php
namespace Modules\Transport\Http\Controllers;

use Modules\Transport\Http\Requests\MechanicRequest;
use Modules\Transport\Http\Resources\MechanicCollection; 
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Http\Controllers\Controller;
use Modules\Transport\Models\Mechanic;
use Illuminate\Http\Request;

class MechanicController extends Controller
{

    public function index()
    {
        return view('transport::mechanics.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número',
        ];
    }

    public function records(Request $request)
    {

        $records = Mechanic::where($request->column, 'like', "%{$request->value}%")
                            ->orderBy('name');

        return new MechanicCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function tables()
    {
        $identity_document_types = IdentityDocumentType::whereActive()->get();

        return compact('identity_document_types');
    }

    public function record($id)
    {
        $record = Mechanic::findOrFail($id);

        return $record;
    }

    public function store(MechanicRequest $request)
    {

        $id = $request->input('id');
        $record = Mechanic::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Mecánico editado con éxito':'Mecánico registrado con éxito',
            'id' => $record->id
        ];
    }

    public function destroy($id)
    {

        $record = Mechanic::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Mecánico eliminado con éxito'
        ];

    }

}
