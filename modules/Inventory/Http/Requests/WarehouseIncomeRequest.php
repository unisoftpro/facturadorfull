<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseIncomeRequest  extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'warehouse_id' => [
                'required',
            ],
            'warehouse_income_reason_id' => [
                'required',
            ],
            'supplier_id' => [
                'required',
            ],
            /*
            'purchase_order_id' => [
                'required_if:warehouse_income_reason_id,"103", "104"',
            ],*/
            'date_of_issue' => [
                'required',
            ]

        ];
    }


}
