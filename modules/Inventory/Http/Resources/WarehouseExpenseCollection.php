<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehouseExpenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            return [
                'id' => $row->id,
                'date_of_issue' => $row->date_of_issue,
                'reference_date' => $row->reference_date,
                'number' => $row->number,
               // 'warehouse_id' => $row->warehouse_id,
                'warehouse_expense_description' => $row->warehouse_expense->description,
                'warehouse_destination_description' => $row->warehouse_destination->description,
                'warehouse_expense_reason_id' => $row->warehouse_expense_reason_id,
                'warehouse_expense_reason_description' => $row->warehouse_expense_reason->description,
                'currency_type_id' => $row->currency_type_id,
                'currency_type_description' => $row->currency_type->description,
                'original_total' => $row->original_total,
                'national_total' => $row->national_total,
            ];
        });
    }
}
