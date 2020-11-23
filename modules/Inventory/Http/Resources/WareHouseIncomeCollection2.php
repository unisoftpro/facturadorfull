<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Inventory\Models\WarehouseIncomeItem;

class WareHouseIncomeCollection2 extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $warehouseitems = WarehouseIncomeItem::where('warehouse_income_id','=',$this->id)->get();

        return [
            'id'=> $this->id,
            'observation'=> $this->observation,
            'cat_document_types_id'=>$this->cat_document_types_id,
            'document_reference'=>$this->document_reference,
            'warehouse_id' => $this->warehouse_id,
            'warehouse_income_reason_id'=>$this->warehouse_income_reason_id,
            'supplier_id'=>$this->supplier_id,
            'reference_date'=>$this->reference_date,
            'date_of_issue'=>$this->date_of_issue,
            'currency_type_id'=>$this->currency_type_id,
            'items'=> $warehouseitems,
            'purchase_order_id'=>$this->purchase_order_id,
            'work_order_id'=>$this->work_order_id
        ];
    }

}
