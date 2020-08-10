<?php

namespace Modules\Transport\Models;

use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\ModelTenant;

class Mechanic extends ModelTenant
{

 
    protected $fillable = [
        'identity_document_type_id',
        'number',
        'name',
        'address',
        'telephone',
    ];


    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

}
