<?php

namespace Modules\Transport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FuelTypeRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        
        $id = $this->input('id');
        
        return [
             
            'description' => [
                'required',
            ]
        ];

    }
}
