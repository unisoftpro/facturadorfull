<?php

namespace Modules\Transport\Models\Vehicles;
 
use App\Models\Tenant\ModelTenant;

class Insurance extends ModelTenant
{

    protected $table = 'insurance';
    
    protected $fillable = [
        'description', 
    ];

}
