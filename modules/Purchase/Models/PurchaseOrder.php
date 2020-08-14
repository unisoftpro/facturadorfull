<?php

namespace Modules\Purchase\Models;

use App\Models\Tenant\User;
use App\Models\Tenant\SoapType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\StateType;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use Modules\Sale\Models\SaleOpportunity;
use Modules\Item\Models\Line;
use Modules\Item\Models\Family;
use Modules\Transport\Models\WorkOrder;
use Modules\Inventory\Models\PurchaseOrderIncome;


class PurchaseOrder extends ModelTenant
{

    protected $fillable = [
        'user_id',
        'external_id',
        'prefix',
        'establishment_id',
        'soap_type_id',
        'state_type_id',
        'date_of_issue',
        'time_of_issue',
        'date_of_due',
        'supplier_id',
        'supplier',
        'currency_type_id',
        'exchange_rate_sale',
        'total_prepayment',
        'total_discount',
        'total_charge',
        'total_exportation',
        'total_free',
        'total_taxed',
        'total_unaffected',
        'total_exonerated',
        'total_igv',
        'total_base_isc',
        'total_isc',
        'total_base_other_taxes',
        'total_other_taxes',
        'total_taxes',
        'total_value',
        'total',
        'filename',
        'upload_filename',
        'purchase_quotation_id',
        'payment_method_type_id',
        'sale_opportunity_id',

        'purchase_order_state_id',
        'purchase_order_type_id',
        'line_id',
        'family_id',
        'observation',
        'reference',
        'place_of_delivery',
        'work_order_id',

    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_due' => 'date',
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

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function supplier() {
        return $this->belongsTo(CurrencyType::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->prefix.'-'.$this->id;
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function purchase_quotation()
    {
        return $this->belongsTo(PurchaseQuotation::class);
    }

    public function sale_opportunity()
    {
        return $this->belongsTo(SaleOpportunity::class);
    }

    public function purchase_order_state()
    {
        return $this->belongsTo(PurchaseOrderState::class);
    }

    public function purchase_order_type()
    {
        return $this->belongsTo(PurchaseOrderType::class);
    }

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function work_order()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function scopeWhereConfirmed($query)
    {
        return $query->where('purchase_order_state_id', '03')
                    ->whereHas('work_order')
                    ->with(['items' => function($q){
                        $q->where('pending_quantity_income', '>', 0);
                    }]);
    }

    public function purchase_order_income()
    {
        return $this->hasMany(PurchaseOrderIncome::class);
    }
}
