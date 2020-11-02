<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');

        return [
            'supplier_id' => [
                'required'
            ],
            'work_order_id'=>[
                'required'
            ]

        ];
    }

    public function messages()
    {
        return [
            'supplier_id.required' => 'El campo Proveedor es obligatorio.',
            'work_order_id.required' => 'El campo Orden de Trabajo es obligatorio.',
        ];
    }
}
