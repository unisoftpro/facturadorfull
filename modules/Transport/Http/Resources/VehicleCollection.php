<?php

namespace Modules\Transport\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VehicleCollection extends ResourceCollection
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
                'license_plate' => $row->license_plate,
                'registration_starting' => $row->registration_starting,
                'title' => $row->title,
                'date' => $row->date,
                'owner_type' => $row->owner_type,
                'owner_description' => $row->owner_description,
                'acquisition_date' => $row->acquisition_date, 
        
            ];

        });

    }
    
}
