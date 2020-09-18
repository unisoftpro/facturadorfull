<?php

namespace App\Http\ViewComposers\Tenant;

use App\Models\Tenant\Establishment;

class EstablishmentViewComposer
{
    public function compose($view)
    {
        $view->vc_establishment = Establishment::find(auth()->user()->establishment_id);
    }
}