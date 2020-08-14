<?php

namespace Modules\Inventory\Models;
 
use App\Models\Tenant\ModelTenant; 
use App\Models\Tenant\SoapType;
use Modules\Purchase\Models\PurchaseOrder;

class PurchaseOrderIncome extends ModelTenant
{
    
    protected $table = 'purchase_order_income';

    protected $fillable = [
        'soap_type_id',
        'date_of_issue',
        'number',
        'invoice_description',
        'warehouse_id',
        'purchase_order_id',
    ];
 
    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    
    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
    
}
