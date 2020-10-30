<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TypeListPriceCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            return [
                'id' => $row->id,
                'currency_type_id' => $row->currency_type_id,
                'description' => $row->description,
                'list_type_id' => $row->list_type_id,
                'name' => $row->name,
                'currencydescription'=>$row->currencydescription,
            ];
        });
    }
}
