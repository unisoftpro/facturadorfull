<?php

namespace Modules\Transport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkOrderRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        
        $id = $this->input('id');
        
        return [
             
            'establishment_id' => [
                'required',
            ],
            'customer_id' => [
                'required',
            ],
            'process_id' => [
                'required',
            ],
            'opening_date' => [
                'required',
            ], 
            'final_item_warehouse_id' => [
                'required',
            ],
            'process_warehouse_id' => [
                'required',
            ],
            'origin_warehouse_id' => [
                'required',
            ],
            'detail' => [
                'required',
            ],
            'incoming_items' => [
                'required',
                'numeric',
                'min:0'
            ],
            'result_items' => [
                'required',
                'numeric',
                'min:0'
            ],
            'difference' => [
                'required',
                'numeric',
                'min:0'
            ],
            'lot_number' => [
                'required',
            ],
            'start_date' => [
                'required',
            ],
            'start_time' => [
                'required',
            ],
            'end_date' => [
                'nullable',
                'after:start_date',
            ],
            'hours' => [
                'required',
                'numeric',
                'min:0'
            ],
            'license_plate' => [
                'required',
            ],
            'mileage' => [
                'required',
                'numeric',
                'min:0'
            ],
            'activity_type_id' => [
                'required',
            ],
        ];

    }
}
