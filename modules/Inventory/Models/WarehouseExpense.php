<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\User;
use App\Models\Tenant\SoapType;
use App\Models\Tenant\Catalogs\CurrencyType;
use Modules\Transport\Models\WorkOrder;
use App\Models\Tenant\Person;
use Modules\Purchase\Models\PurchaseOrder;

class WarehouseExpense extends ModelTenant
{

    protected $table = 'warehouse_expense';

    protected $fillable = [
        'user_id',
        'soap_type_id',
        'external_id',
        'warehouse_expense_id',
        'warehouse_destination_id',
        'warehouse_expense_reason_id',
        'date_of_issue',
        'supplier_id',
        'supplier',
        'currency_type_id',
        'exchange_rate_sale',
        'observation',
        'number',
        'reference_date',
        'purchase_order_id',
        'work_order_id',
        'original_total',
        'national_total',
        'total_value',
        'total',
        'filename',
    ];


    public function getSupplierAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setSupplierAttribute($value)
    {
        $this->attributes['supplier'] = (is_null($value))?null:json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function person() {
        return $this->belongsTo(Person::class, 'supplier_id');
    }

    public function work_order()
    {
        return $this->belongsTo(WorkOrder::class);
    }


    public function warehouse_expense()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_expense_id');
    }

    public function warehouse_destination()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_destination_id');
    }

    public function warehouse_expense_reason()
    {
        return $this->belongsTo(WarehouseExpenseReason::class);
    }

    public function items()
    {
        return $this->hasMany(WarehouseExpenseItem::class);
    }

    public function inventory_kardex()
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }

}
