<?php

namespace Modules\Transport\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,  
            'external_id' => $this->external_id,
            'soap_type_id' => $this->soap_type_id,
            'establishment_id' => $this->establishment_id,
            'customer_id' => $this->customer_id,
            'process_id' => $this->process_id,
            'opening_date' => $this->opening_date,
            'work_order_state_id' => $this->work_order_state_id,
            'final_item_warehouse_id' => $this->final_item_warehouse_id,
            'process_warehouse_id' => $this->process_warehouse_id,
            'origin_warehouse_id' => $this->origin_warehouse_id,
            'detail' => $this->detail,
            'control_number' => $this->control_number,
            'incoming_items' => $this->incoming_items,
            'result_items' => $this->result_items,
            'difference' => $this->difference,
            'number' => $this->number,
            'lot_number' => $this->lot_number,
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'end_date' => $this->end_date,
            'end_time' => $this->end_time,
            'hours' => $this->hours,
            'license_plate' => $this->license_plate,
            'mileage' => $this->mileage,
            'activity_type_id' => $this->activity_type_id,
        ];
    }
}
