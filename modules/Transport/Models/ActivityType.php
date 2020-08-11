<?php

namespace Modules\Transport\Models;
 
use App\Models\Tenant\ModelTenant;

class ActivityType extends ModelTenant
{

    protected $fillable = [
        'description', 
    ];

}
