<?php

namespace Modules\Transport\Models;
 
use App\Models\Tenant\ModelTenant;

class MaintenanceType extends ModelTenant
{

    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'description', 
    ];

}
