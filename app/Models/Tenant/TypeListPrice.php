<?php

namespace App\Models\Tenant;

class TypeListPrice extends ModelTenant
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'description'];
    public $incrementing = false;
    public $timestamps = false;



}
