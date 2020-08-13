<?php

namespace Modules\Purchase\Models;
 
use App\Models\Tenant\ModelTenant;

class PurchaseOrderState extends ModelTenant
{

    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'description', 
    ];

}
