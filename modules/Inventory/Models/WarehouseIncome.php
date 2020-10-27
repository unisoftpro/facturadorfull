<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\User;
use App\Models\Tenant\SoapType;
use App\Models\Tenant\Catalogs\CurrencyType;
use Modules\Transport\Models\WorkOrder;
use App\Models\Tenant\Person;
use Modules\Purchase\Models\PurchaseOrder;
use App\Models\Tenant\Catalogs\DocumentType;

class WarehouseIncome extends ModelTenant
{

    protected $table = 'warehouse_income';

    protected $fillable = [
        'user_id',
        'soap_type_id',
        'external_id',
        'warehouse_id',
        'warehouse_income_reason_id',
        'date_of_issue',
        'supplier_id',
        'supplier',
        'currency_type_id',
        'exchange_rate_sale',
        'observation',
        'number',
        'reference_date',
        'cat_document_types_id',
        'document_reference',
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

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function warehouse_income_reason()
    {
        return $this->belongsTo(WarehouseIncomeReason::class);
    }

    public function items()
    {
        return $this->hasMany(WarehouseIncomeItem::class);
    }

    public function inventory_kardex()
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }
    public function documents_type(){
        return $this->belongsTo(DocumentType::class,'cat_document_types_id');
    }

}
