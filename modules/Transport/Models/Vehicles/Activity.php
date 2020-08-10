<?php

namespace Modules\Transport\Models\Vehicles;
 
use App\Models\Tenant\ModelTenant;

class Activity extends ModelTenant
{
    
    protected $fillable = [
        'description', 
        'transport_group_id', 
    ];

    public function transport_group() 
    {
        return $this->belongsTo(TransportGroup::class);
    }

}
