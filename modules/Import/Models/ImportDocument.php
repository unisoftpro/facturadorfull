<?php

namespace Modules\Import\Models;

use App\Models\Tenant\User;
use App\Models\Tenant\Document; 
use App\Models\Tenant\ModelTenant;

class ImportDocument extends ModelTenant
{


    protected $fillable = [
        'user_id', 
    ];

    
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    

}