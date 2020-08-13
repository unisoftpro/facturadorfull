<?php

namespace Modules\Purchase\Models;
 
use App\Models\Tenant\ModelTenant;

class PurchaseOrderType extends ModelTenant
{

    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'description', 
    ];

}
