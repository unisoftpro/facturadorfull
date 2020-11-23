<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportPrecieListCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            return [
                'id' => $row->id,
                'description' => $row->description,
                'descriptionf' => $row->descriptionf,
                'descripctionb' => $row->descripctionb,
                'descriptionl' => $row->descriptionl,
                'stock' => $row->stock,
                'item_code' => $row->item_code,
                'unit_type_id' => $row->unit_type_id,
                'price_list'=>$row->price_list,
                'currency_type_id'=>$row->currency_type_id,
            ];
        });
    }
}
