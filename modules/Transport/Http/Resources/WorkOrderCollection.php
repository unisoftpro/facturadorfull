<?php

namespace Modules\Transport\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkOrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            return [
                'id' => $row->id,
                'user_id' => $row->user_id,
                'user_name' => $row->user->name,
                'customer_id' => $row->customer_id,
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->number,
                'opening_date' => $row->opening_date,
                'process_id' => $row->process_id,
                'process_description' => $row->process->description,
                'work_order_state_id' => $row->work_order_state_id,
                'work_order_state_description' => $row->work_order_state->description,
                'number' => $row->number,
                'control_number' => $row->control_number, 
            ];

        });

    }
    
}
