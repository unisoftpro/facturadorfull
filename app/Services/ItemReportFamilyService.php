<?php

namespace App\Services;
use Modules\Inventory\Models\WarehouseIncomeItem;


class ItemReportFamilyService
{

    public function GroupedByFamily($id)
    {
        $items = WarehouseIncomeItem::where('warehouse_income_id', $id)->get();

        $records = $items->transform(function($row){
           // $full = explode(' - ', $row->item->full_description);
            return (object)[
                'family' => ($row->family) ? $row->family->name : 'No definido',
                'code' => $row->relation_item->item_code,
                'name' => $row->item->description,
                'unit' => $row->item->unit_type_id,
                'quantity' => $row->quantity,
                'sub_total' => $row->total_value,
                'total' => $row->total,
            ];
        });

        $grouped = $records->groupBy('family');
        return $grouped->toArray();
    }


}
