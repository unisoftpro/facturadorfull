<?php

namespace Modules\Inventory\Traits;

use Exception; 

trait UtilityTrait
{
    
    public function setFilename($record){

        $name = [$record->number,$record->id,date('Ymd')];
        $record->filename = join('-', $name);
        $record->save();

    }


    public function newNumber($model){

        $number = $model::select('number')->max('number');
        return ($number) ? (int)$number + 1 : 1;

    }

}
