<?php

namespace Modules\Transport\Models\Vehicles;
 
use App\Models\Tenant\ModelTenant;

class VehicleBrand extends ModelTenant
{

    protected $fillable = [
        'description', 
    ];

}
