<?php

namespace Modules\Transport\Models\Vehicles;
 
use App\Models\Tenant\ModelTenant;

class VehicleType extends ModelTenant
{

    protected $fillable = [
        'description', 
    ];

}
