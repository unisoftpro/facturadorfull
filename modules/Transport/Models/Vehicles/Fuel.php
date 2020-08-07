<?php

namespace Modules\Transport\Models\Vehicles;
 
use App\Models\Tenant\ModelTenant;

class Fuel extends ModelTenant
{

    protected $fillable = [
        'description', 
        'fuel_type_id', 
    ];

    public function fuel_type() 
    {
        return $this->belongsTo(FuelType::class);
    }

}
