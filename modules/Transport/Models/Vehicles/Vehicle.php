<?php

namespace Modules\Transport\Models\Vehicles;
 
use App\Models\Tenant\Person;
use App\Models\Tenant\ModelTenant;

class Vehicle extends ModelTenant
{

    protected $fillable = [
        
        'license_plate',
        'old_license_plate',
        'registration_starting',
        'title',
        'date',
        'condition',
        'owner_type',
        'denomination',
        'lastname',
        'mother_lastname',
        'names',
        'acquisition_date',
        'expedition_date',
        'temporary_validity',
        'address',
        'category',
        'vehicle_brand_id',
        'color_id',
        'motor',
        'fuel_id',
        'rolling_form',
        'vin',
        'serie',
        'manufacturing_year',
        'model',
        'model_year',
        'version',
        'axes',
        'seating',
        'passengers',
        'wheel',
        'bodywork',
        'power',
        'cylinders',
        'displacement',
        'gross_weight',
        'net_weight',
        'useful_load',
        'length',
        'height',
        'width',
        'vehicle_type_id',
        'insurance_date_of_due',
        'insurance_id',
        'customer_id',

    ];


    public function insurance() 
    {
        return $this->belongsTo(Insurance::class);
    }

    public function vehicle_type() 
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function vehicle_brand() 
    {
        return $this->belongsTo(VehicleBrand::class);
    }

    public function color() 
    {
        return $this->belongsTo(Color::class);
    }

    public function fuel() 
    {
        return $this->belongsTo(Fuel::class);
    }

    public function customer() 
    {
        return $this->belongsTo(Person::class, 'customer_id');
    }

    public function getOwnerDescriptionAttribute()
    {
        return "{$this->lastname} {$this->mother_lastname} {$this->names}";
    }

}
