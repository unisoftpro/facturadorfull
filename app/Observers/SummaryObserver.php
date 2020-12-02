<?php

namespace App\Observers;

use App\CoreFacturalo\Requests\Inputs\Functions;
use App\Models\Tenant\Company;
use App\Models\Tenant\Summary;

class SummaryObserver
{

    /**
     * Handle the summary "creating" event.
     *
     * @param  \App\Models\Tenant\Summary  $summary
     * @return void
     */
    public function creating(Summary $summary)
    {
        // dd($summary->soap_type_id, $summary->date_of_issue->format('Y-m-d'), Summary::class);

        $company = Company::active();

        $identifier = Functions::identifier($summary->soap_type_id, $summary->date_of_issue->format('Y-m-d'), Summary::class);
        $filename = $company->number.'-'.$identifier;
 
        $summary->identifier = $identifier;
        $summary->filename = $filename;


    }
 

}
