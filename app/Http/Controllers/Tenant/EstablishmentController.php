<?php
namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\EstablishmentRequest;
use App\Http\Resources\Tenant\EstablishmentResource;
use App\Http\Resources\Tenant\EstablishmentCollection;
use App\Models\Tenant\Warehouse;
use App\Models\Tenant\Person;
use Illuminate\Http\Request;
use Modules\Finance\Helpers\UploadFileHelper;
use Illuminate\Support\Facades\Storage;


class EstablishmentController extends Controller
{
    public function index()
    {
        return view('tenant.establishments.index');
    }

    public function create()
    {
        return view('tenant.establishments.form');
    }

    public function tables()
    {
        $countries = Country::whereActive()->orderByDescription()->get();
        $departments = Department::whereActive()->orderByDescription()->get();
        $provinces = Province::whereActive()->orderByDescription()->get();
        $districts = District::whereActive()->orderByDescription()->get();

        $customers = Person::whereType('customers')->orderBy('name')->take(1)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
            ];
        });

        return compact('countries', 'departments', 'provinces', 'districts', 'customers');
    }

    public function record($id)
    {
        $record = new EstablishmentResource(Establishment::findOrFail($id));

        return $record;
    }

    public function store(EstablishmentRequest $request)
    {
        $id = $request->input('id');
        $establishment = Establishment::firstOrNew(['id' => $id]);
        $establishment->fill($request->all());
        $establishment->save();

        if(!$id) {
            $warehouse = new Warehouse();
            $warehouse->establishment_id = $establishment->id;
            $warehouse->description = 'Almacén - '.$establishment->description;
            $warehouse->save();
        }

        $this->saveLogo($establishment, $request);

        return [
            'success' => true,
            'message' => ($id)?'Establecimiento actualizado':'Establecimiento registrado'
        ];
    }


    public function saveLogo($establishment, $request)
    {

        $temp_path = $request->input('temp_path');

        if($temp_path) {

            $company = Company::active();
            $directory = 'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'logos'.DIRECTORY_SEPARATOR;
            $file_name_old = $request->input('logo');
            $file_name_old_array = explode('.', $file_name_old);
            $file_content = file_get_contents($temp_path);
            $datenow = date('YmdHis');
            $file_name = 'logo_'.$establishment->id.'_'.$company->number.'_'.$datenow.'.'.$file_name_old_array[1];
            Storage::put($directory.$file_name, $file_content);
            $establishment->logo = $file_name;

        }

        $establishment->save();
    }


    public function records()
    {
        $records = Establishment::all();

        return new EstablishmentCollection($records);
    }

    public function destroy($id)
    {
        $establishment = Establishment::findOrFail($id);
        $establishment->delete();

        return [
            'success' => true,
            'message' => 'Establecimiento eliminado con éxito'
        ];
    }

    
    public function uploadFile(Request $request)
    {

        $validate_upload = UploadFileHelper::validateUploadFile($request, 'file', 'jpg,jpeg,png,gif,svg');

        if(!$validate_upload['success']){
            return $validate_upload;
        }

        if ($request->hasFile('file')) {
            $new_request = [
                'file' => $request->file('file'),
                'type' => $request->input('type'),
            ];

            return $this->upload_image($new_request);
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }


    function upload_image($request)
    {
        $file = $request['file'];
        $type = $request['type'];

        $temp = tempnam(sys_get_temp_dir(), $type);
        file_put_contents($temp, file_get_contents($file));

        return [
            'success' => true,
            'data' => [
                'filename' => $file->getClientOriginalName(),
                'temp_path' => $temp,
            ]
        ];
    }

    public function uploadFileLogo(Request $request)
    {

        if ($request->hasFile('file')) {

            $company = Company::active();
            $establishment = Establishment::findOrFail(auth()->user()->establishment_id);
 
            $type = $request->input('type');
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();

            $datenow = date('YmdHis');
            $name = 'logo_'.$establishment->id.'_'.$company->number.'_'.$datenow.'.'.$ext;

            request()->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

            $file->storeAs(($type === 'logo') ? 'public/uploads/logos' : 'certificates', $name);

            $establishment->logo = $name;
            $establishment->save();

            return [
                'success' => true,
                'message' => __('app.actions.upload.success'),
                'name' => $name,
                'type' => $type
            ];
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }


}