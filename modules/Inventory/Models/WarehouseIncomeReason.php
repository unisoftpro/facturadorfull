<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\ModelTenant;

class WarehouseIncomeReason extends ModelTenant
{

    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'description', 
    ];
}
