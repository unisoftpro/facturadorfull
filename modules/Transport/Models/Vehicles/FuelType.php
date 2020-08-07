<?php

namespace Modules\Transport\Models\Vehicles;
 
use App\Models\Tenant\ModelTenant;

class FuelType extends ModelTenant
{

    protected $fillable = [
        'description', 
    ];

}
