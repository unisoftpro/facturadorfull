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
            'warehouse_id' => $this->warehouse_id,
            'warehouse_income_reason_id'=>$this->warehouse_income_reason_id,
            'supplier_id'=>$this->supplier_id,
            'reference_date'=>$this->reference_date,
            'date_of_issue'=>$this->date_of_issue,
            'currency_type_id'=>$this->currency_type_id,
            'items'=> $warehouseitems
        ];
    }

}
