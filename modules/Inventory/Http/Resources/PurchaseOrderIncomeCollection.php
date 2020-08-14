<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseOrderIncomeCollection extends ResourceCollection
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
                'number' => $row->number,
                'invoice_description' => $row->invoice_description,
                'warehouse_id' => $row->warehouse_id,
                'warehouse_description' => $row->warehouse->description,
                'purchase_order_number' => $row->purchase_order->id,
            ];
        });
    }
}