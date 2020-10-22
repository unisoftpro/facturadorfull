<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportValutedBalancesCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            return [
                'id' => $row->id,
                'description' => $row->description,
                'descriptionf' => $row->descriptionf,
                'descriptionl' => $row->descriptionl,
                'stock' => $row->stock,
                'item_code' => $row->item_code,
                'currency_type_id' => $row->currency_type_id,
                'Totalart'=>$row->Totalart,
                'price_fob'=>$row->price_fob,
                'internal_id'=>$row->internal_id,
            ];
        });
    }
}
