<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use App\CoreFacturalo\Facturalo;
use App\Models\Tenant\Document;
use App\Models\Tenant\StateType;
use Illuminate\Http\Request;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Hyn\Tenancy\Queue\TenantAwareJob;

class DocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, TenantAwareJob;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $request2;
    // public $website_id;
    
    public function __construct($request)
    {
        // $this->website_id = $website_id;
        $this->request2 = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $fact = DB::connection('tenant')->transaction(function () {
            $facturalo = new Facturalo();
            $facturalo->save($this->request2);
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->updateHash();
            $facturalo->updateQr();
            $facturalo->createPdf();
            $facturalo->sendEmail();
            $facturalo->senderXmlSignedBill();

            return $facturalo;
        });

        $document = $fact->getDocument();
        $response = $fact->getResponse();

        return [
            'success' => true,
            'data' => [
                'number' => $document->number_full,
                'filename' => $document->filename,
                'external_id' => $document->external_id,
                'state_type_id' => $document->state_type_id,
                // 'state_type_description' => $this->getStateTypeDescription($document->state_type_id),
                'number_to_letter' => $document->number_to_letter,
                'hash' => $document->hash,
                'qr' => $document->qr,
            ],
            'links' => [
                // 'xml' => $document->download_external_xml,
                // 'pdf' => $document->download_external_pdf,
                // 'cdr' => ($response['sent'])?$document->download_external_cdr:'',
            ],
            'response' => ($response['sent'])?array_except($response, 'sent'):[]
        ];
    }
}
