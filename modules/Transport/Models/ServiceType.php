<?php

namespace Modules\Transport\Models;
 
use App\Models\Tenant\ModelTenant;

class ServiceType extends ModelTenant
{

    protected $fillable = [
        'description', 
    ];

}
