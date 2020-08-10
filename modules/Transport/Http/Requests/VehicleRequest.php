<?php

namespace Modules\Transport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        
        $id = $this->input('id');
        return [
             
            'license_plate' => [
                'required',
            ],
            'old_license_plate' => [
                'required',
            ],
            'registration_starting' => [
                'required',
            ],
            'title' => [
                'required',
            ],
            'date' => [
                'required',
            ],
            'condition' => [
                'required',
            ],
            'owner_type' => [
                'required',
            ],
            'denomination' => [
                'required',
            ],
            'lastname' => [
                'required',
            ],
            'mother_lastname' => [
                'required',
            ],
            'names' => [
                'required',
            ],
            'acquisition_date' => [
                'required',
            ],
            'expedition_date' => [
                'required',
            ],
            'temporary_validity' => [
                'required',
            ],
            'address' => [
                'required',
            ],
            'category' => [
                'required',
            ],
            'vehicle_brand_id' => [
                'required',
            ],
            'color_id' => [
                'required',
            ],
            'motor' => [
                'required',
            ],
            'fuel_id' => [
                'required',
            ],
            'rolling_form' => [
                'required',
            ],
            'vin' => [
                'required',
            ],
            'serie' => [
                'required',
            ],
            'manufacturing_year' => [
                'required',
            ],
            'model_year' => [
                'required',
            ],
            'model' => [
                'required',
            ],
            'version' => [
                'required',
            ],
            'axes' => [
                'required',
            ],
            'seating' => [
                'required',
            ],
            'passengers' => [
                'required',
            ],
            'wheel' => [
                'required',
            ],
            
            'bodywork' => [
                'required',
            ],
            'power' => [
                'required',
            ],
            'cylinders' => [
                'required',
            ],
            'displacement' => [
                'required',
            ],
            'gross_weight' => [
                'required',
            ],
            'net_weight' => [
                'required',
            ],
            'useful_load' => [
                'required',
            ],
            'length' => [
                'required',
            ],
            'height' => [
                'required',
            ],
            'width' => [
                'required',
            ],
            'vehicle_type_id' => [
                'required',
            ],
            'insurance_date_of_due' => [
                'required',
            ],
            'insurance_id' => [
                'required',
            ],
            'customer_id' => [
                'required',
            ],
        ];

    }
}
