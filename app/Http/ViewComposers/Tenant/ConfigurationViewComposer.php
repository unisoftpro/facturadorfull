<?php

namespace App\Http\ViewComposers\Tenant;

use App\Models\Tenant\Configuration;

class ConfigurationViewComposer
{
    public function compose($view)
    {
        $view->vc_configuration = Configuration::first();
    }
}