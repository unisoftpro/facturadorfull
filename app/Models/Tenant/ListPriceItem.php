<?php

namespace App\Models\Tenant;

class ListPriceItem extends ModelTenant
{

    protected $table = 'list_price_item';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'item_id', 'currency_type_id', 'list_type_id', 'price_fob', 'factor', 'price_list', 'discount_one', 'discount_two', 'discount_three'];


    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function type()
    {
        return $this->belongsTo(TypeListPrice::class, 'list_type_id');
    }

}
