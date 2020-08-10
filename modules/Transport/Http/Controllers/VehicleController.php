<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Vehicles\Vehicle;
use Modules\Transport\Models\Vehicles\VehicleType;
use Modules\Transport\Models\Vehicles\Insurance;
use Modules\Transport\Models\Vehicles\VehicleBrand;
use Modules\Transport\Models\Vehicles\Color;
use Modules\Transport\Models\Vehicles\Fuel;
use App\Models\Tenant\Person;
use Modules\Transport\Http\Resources\VehicleCollection;
use Modules\Transport\Http\Requests\VehicleRequest;

class VehicleController extends Controller
{

    public function index()
    {
        return view('transport::vehicles.index');
    }


    public function columns()
    {
        return [
            'license_plate' => 'N° Placa',
            'lastname' => 'Apellido paterno',
            'names' => 'Nombres',
        ];
    }

    public function records(Request $request)
    {
        $records = Vehicle::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new VehicleCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function tables()
    {
        $customers = $this->table('customers');
        $vehicle_types = VehicleType::get();
        $insurances = Insurance::get();
        $vehicle_brands = VehicleBrand::get();
        $colors = Color::get();
        $fuels = Fuel::get();

        return compact('customers', 'vehicle_types', 'insurances', 'vehicle_brands', 'colors', 'fuels');
    }


    public function table($table)
    {
        switch ($table) {
            case 'customers':

                $customers = Person::whereType('customers')->whereIsEnabled()->orderBy('name')->take(20)->get()->transform(function($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number.' - '.$row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                    ];
                });
                return $customers;

                break;

            default:
                return [];

                break;
        }
    }


    public function record($id)
    {
        $record = Vehicle::findOrFail($id);

        return $record;
    }

    public function store(VehicleRequest $request)
    {
        $id = $request->input('id');
        $record = Vehicle::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();


        return [
            'success' => true,
            'message' => ($id)?'Vehículo editado con éxito':'Vehículo registrado con éxito',
        ];

    }

    public function destroy($id)
    {

        $record = Vehicle::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Vehículo eliminado con éxito'
        ];

    }


    public function searchCustomers(Request $request)
    {

        $customers = Person::where('number','like', "%{$request->input}%")
                            ->orWhere('name','like', "%{$request->input}%")
                            ->whereType('customers')->orderBy('name')
                            ->whereIsEnabled()
                            ->get()->transform(function($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => $row->number.' - '.$row->name,
                                    'name' => $row->name,
                                    'number' => $row->number,
                                    'identity_document_type_id' => $row->identity_document_type_id,
                                ];
                            });

        return compact('customers');
    }


    public function searchCustomerById($id)
    {

        $customers = Person::whereType('customers')
                    ->where('id',$id)
                    ->get()->transform(function($row) {
                        return [
                            'id' => $row->id,
                            'description' => $row->number.' - '.$row->name,
                            'name' => $row->name,
                            'number' => $row->number,
                            'identity_document_type_id' => $row->identity_document_type_id,
                        ];
                    });

        return compact('customers');
    }


}
