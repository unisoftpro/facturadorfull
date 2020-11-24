<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Inventory\Models\WarehouseExpense;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Models\PurchaseOrderItem;

class ReportValutedBalancesCollection extends ResourceCollection
{

    public function toArray($request)
    {


        return $this->collection->transform(function ($row, $key) {
            $purchase_order_item = PurchaseOrderItem::where('item_id', $row->item_id)->latest('id')->first();
            $salida = WarehouseExpense::join('warehouse_expense_items', 'warehouse_expense.id', '=', 'warehouse_expense_items.warehouse_expense_id')
                ->join('items', 'items.id', '=', 'warehouse_expense_items.item_id')
                ->join('families', 'families.id', 'items.family_id')
                ->join('lines', 'lines.id', '=', 'items.line_id')
                ->select(
                    DB::raw('sum(warehouse_expense_items.quantity) as salidas')
                )
                ->where('warehouse_expense_items.item_id',$row->item_id)
                ->where('warehouse_expense.warehouse_expense_id',$row->warehouse_id)
                ->where('families.id',$row->idF)
                ->where('lines.id',$row->idL)
                ->groupBy('warehouse_expense_items.item_id', 'warehouse_expense.warehouse_expense_id', 'items.name', 'families.name', 'lines.name', 'families.id', 'lines.id')->get();
            if(count($salida)>0){
                $saldo= (int) $row->ingresos - (int)  $salida[0]['salidas'];
            }else{
                $saldo = (int) $row->ingresos;
            }
            return [
                'item_code' => $row->item_code,
                'description'=> $row->description,
                'warehouse_id' => $row->warehouse_id,
                'name' => $row->name,
                'descriptionf' => $row->descriptionf,
                'descriptionl' => $row->descriptionl,
                'saldos' => $saldo,
                'idF' => $row->idF,
                'idL' => $row->idL,
                'price_fob'=>round($purchase_order_item->unit_price,3),
                'Totalart'=>$saldo*round($purchase_order_item->unit_price,3),
                'currency_type_id'=>$purchase_order_item->purchase_order->currency_type_id,
            ];
        });
    }
}
