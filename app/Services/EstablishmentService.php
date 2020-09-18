<?php

namespace App\Services;

use App\Models\Tenant\Establishment;
use App\Models\Tenant\User;

class EstablishmentService
{

    public static function getLogo($establishment_id)
    {
        $establishment = Establishment::select('logo')->find($establishment_id);

        return $establishment->logo;
    }


    public static function getLogoByUser($user_id)
    {
        $establishment_id = User::select('establishment_id')->find($user_id)->establishment_id;
        return self::getLogo($establishment_id);
    }

}
