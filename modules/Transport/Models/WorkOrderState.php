<?php

namespace Modules\Transport\Models;
 
use App\Models\Tenant\ModelTenant;

class WorkOrderState extends ModelTenant
{

    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'description', 
    ];

}
