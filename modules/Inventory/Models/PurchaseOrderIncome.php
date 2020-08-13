<?php

namespace Modules\Inventory\Models;
 
use App\Models\Tenant\ModelTenant; 

class PurchaseOrderIncome extends ModelTenant
{
    
    protected $table = 'purchase_order_income';

    protected $fillable = [
        'date_of_issue',
        'number',
        'invoice_description',
        'warehouse_destination_id',
    ];
 
    public function warehouse_destination()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_destination_id');
    }
    
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
    
}
