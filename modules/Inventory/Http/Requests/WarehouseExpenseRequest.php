<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseExpenseRequest  extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'warehouse_expense_id' => [
                'required',
            ],
            'warehouse_destination_id' => [
                'required',
            ],
            'warehouse_expense_reason_id' => [
                'required',
            ],
            'supplier_id' => [
                'required',
            ],
            'work_order_id' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ]

        ];
    }


}
