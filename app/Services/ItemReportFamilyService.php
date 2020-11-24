<?php

namespace App\Services;
use Modules\Inventory\Models\WarehouseIncomeItem;
use Modules\Item\Models\Line;
use Modules\Inventory\Models\WarehouseExpenseItem;

class ItemReportFamilyService
{
    public static function getLine($id)
    {
        return Line::find($id)->name;
    }

    public static function getPrice($item_id, $unit_value)
    {
        $row = WarehouseIncomeItem::where('item_id', $item_id)->orderBy('id', 'desc')->first();
        if(!$row) return $unit_value;

        return $row->unit_value;
    }

    public function GroupedByFamily($id)
    {
        $items = WarehouseIncomeItem::where('warehouse_income_id', $id)->get();

        $records = $items->transform(function($row){
            return (object)[
                'family' => ($row->family) ? $row->family->name : 'Familia no definido',
                'line' => ($row->relation_item->line_id) ? self::getLine($row->relation_item->line_id) : 'Linea no definido',
                'brand' => ($row->relation_item->brand) ? $row->relation_item->brand->name : 'Marca no definido',
                'code' => $row->relation_item->internal_id,
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

    public function GroupeByFamilyPreciList($data){

        $records = $data->transform(function($row){
            return (object)[


                'id' => $row->id,
                'description' =>  $row->description,
                'descriptionf' => $row->descriptionf,
                'descripctionb' => $row->descripctionb,
                'descriptionl' => $row->descriptionl,
                'item_code' => $row->item_code,
                'unit_type_id' => $row->unit_type_id,
                'price_list' => $row->price_list,
                'idFamilia' => $row->idFamilia,
                'idBrands' => $row->idBrands,
                'idLines' => $row->idLines,
                'stock' => $row->stock
            ];
        });
        $grouped = $records->groupBy(['descriptionf', 'descriptionl','descripctionb']);
        return $grouped->toArray();
    }
    public function GroupeByFamilySaldos($data){
        $collection = collect($data);
        $records = $collection->transform(function($row){

            return (object)[

                'item_code' => $row['item_code'],
                'description' =>  $row['description'],
                'warehouse_id' => $row['warehouse_id'],
                'name' => $row['name'],
                'descriptionf' => $row['descriptionf'],
                'descriptionl' => $row['descriptionl'],
                'saldos' => $row['saldos'],
                'idF' => $row['idF'],
                'idL' => $row['idL'],
                'price_fob' => $row['price_fob'],
                'Totalart' => $row['Totalart'],
                'currency_type_id' => $row['currency_type_id']
            ];
        });
        $grouped = $records->groupBy(['descriptionf', 'descriptionl']);
        return $grouped->toArray();
    }
    public function GroupedByFamilyExpense($id)
    {
        $items = WarehouseExpenseItem::where('warehouse_expense_id', $id)->get();

        $records = $items->transform(function($row){
            return (object)[
                'family' => ($row->family) ? $row->family->name : 'Familia no definido',
                'line' => ($row->relation_item->line_id) ? self::getLine($row->relation_item->line_id) : 'Linea no definido',
                'brand' => ($row->relation_item->brand) ? $row->relation_item->brand->name : 'Marca no definido',
                'code' => $row->relation_item->internal_id,
                'name' => $row->item->description,
                'unit' => $row->item->unit_type_id,
                'quantity' => $row->quantity,
                'sub_total' => $row->total_value,
                'total' => $row->total,
                'unit_value' => self::getPrice($row->item_id, $row->unit_value),
                'unit_price' => $row->unit_price
            ];
        });

        $grouped = $records->groupBy(['family', 'line']);
        return $grouped->toArray();
    }
    public function GroupedByFamilySale($data)
    {

    }



}
