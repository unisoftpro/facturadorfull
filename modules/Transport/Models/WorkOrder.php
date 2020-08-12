<?php

namespace Modules\Transport\Models;

use App\Models\Tenant\User;
use App\Models\Tenant\SoapType;
use App\Models\Tenant\Person;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\ModelTenant;
use Modules\Inventory\Models\Warehouse;

class WorkOrder extends ModelTenant
{
 
    protected $fillable = [
        'user_id',
        'soap_type_id',
        'external_id',
        'prefix',
        'establishment_id',
        'establishment',
        'customer_id',
        'customer',
        'process_id',
        'opening_date',
        'work_order_state_id',
        'final_item_warehouse_id',
        'process_warehouse_id',
        'origin_warehouse_id',
        'detail',
        'control_number',
        'incoming_items',
        'result_items',
        'difference',
        'number',
        'lot_number',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'hours',
        'license_plate',
        'mileage',
        'activity_type_id',
        'filename',
    ];


    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getCustomerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value))?null:json_encode($value);
    }
    
    public function getNumberFullAttribute()
    {
        return "{$this->prefix}-{$this->number}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }
 
    public function person() {
        return $this->belongsTo(Person::class, 'customer_id');
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    public function work_order_state() 
    {
        return $this->belongsTo(WorkOrderState::class, 'work_order_state_id');
    }

    public function process() 
    {
        return $this->belongsTo(Process::class);
    }
    
    public function origin_warehouse() 
    {
        return $this->belongsTo(Warehouse::class, 'origin_warehouse_id');
    }

    public function process_warehouse() 
    {
        return $this->belongsTo(Warehouse::class, 'process_warehouse_id');
    }

    public function final_item_warehouse() 
    {
        return $this->belongsTo(Warehouse::class, 'final_item_warehouse_id');
    }

    public function activity_type() 
    {
        return $this->belongsTo(ActivityType::class);
    }
    
}
