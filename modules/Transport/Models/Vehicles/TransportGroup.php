<?php

namespace Modules\Transport\Models\Vehicles;
 
use App\Models\Tenant\ModelTenant;

class TransportGroup extends ModelTenant
{

    protected $fillable = [
        'description', 
    ];

}
