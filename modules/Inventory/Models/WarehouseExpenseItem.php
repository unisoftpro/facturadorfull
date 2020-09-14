<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Item\Models\Category;
use Modules\Item\Models\Family;
use App\Models\Tenant\Item;

class WarehouseExpenseItem extends ModelTenant
{

    public $timestamps = false;

    protected $fillable = [
        'warehouse_income_id',
        'item_id',
        'category_id',
        'family_id',
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
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function warehouse_income()
    {
        return $this->belongsTo(WarehouseIncome::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
