<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Item; 

class WarehouseIncomeItem extends ModelTenant
{

    public $timestamps = false;
    
    protected $fillable = [
        'warehouse_income_id',
        'item_id',
        'item',
        'quantity',
        'list_price',
        'discount_one',
        'discount_two',
        'discount_three',
        'discount_four',
        'unit_value',
        'unit_price',
        'sale_profit_factor',
        'price_fob_alm',
        'price_fob_alm_igv',
        'last_purchase_price',
        'warehouse_factor',
        'retail_price',
        'last_factor',
        'num_price',
        'letter_price',
        'total_value',
        'total',
    ];


    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }
    
    public function relation_item()
    {
        return $this->belongsTo(Item::class);
    }
     
    public function warehouse_income()
    {
        return $this->belongsTo(WarehouseIncome::class);
    }
    
}
