<?php

namespace App\Services;
use Modules\Inventory\Models\WarehouseIncomeItem;
use Modules\Item\Models\Line;



class ItemReportFamilyService
{
    public static function getLine($id)
    {
        return Line::find($id)->name;
    }

    public function GroupedByFamily($id)
    {
        $items = WarehouseIncomeItem::where('warehouse_income_id', $id)->get();

        $records = $items->transform(function($row){
            return (object)[
                'family' => ($row->family) ? $row->family->name : 'Familia no definido',
                'line' => ($row->relation_item->line_id) ? self::getLine($row->relation_item->line_id) : 'Linea no definido',
                'brand' => ($row->relation_item->brand) ? $row->relation_item->brand->name : 'Marca no definido',
                'code' => $row->relation_item->internal_idt,
                'name' => $row->item->description,
                'unit' => $row->item->unit_type_id,
                'quantity' => $row->quantity,
                'sub_total' => $row->total_value,
                'total' => $row->total,
                'unit_value' => $row->unit_value,
                'unit_price' => $row->unit_price
            ];
        });

        $grouped = $records->groupBy(['family', 'line']);
        return $grouped->toArray();
    }


}
