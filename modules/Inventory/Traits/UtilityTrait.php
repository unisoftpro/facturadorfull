<?php

namespace Modules\Inventory\Traits;

use Exception; 
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Template;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Company;


trait UtilityTrait
{

    use StorageDocument;


    public function createPdf($record, $format_pdf = "a4", $folder) {

        ini_set("pcre.backtrack_limit", "5000000");
        $template = new Template();
        $pdf = new Mpdf();

        $document = $record;
        $company = Company::active();
        $base_template = Configuration::first()->formats;
        $filename = $record->filename;

        $html = $template->pdf($base_template, $folder, $company, $document, $format_pdf);

        $pdf_font_regular = config('tenant.pdf_name_regular');
        $pdf_font_bold = config('tenant.pdf_name_bold');

        if ($pdf_font_regular != false) {
            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            $pdf = new Mpdf([
                'fontDir' => array_merge($fontDirs, [
                    app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                                DIRECTORY_SEPARATOR.'pdf'.
                                                DIRECTORY_SEPARATOR.$base_template.
                                                DIRECTORY_SEPARATOR.'font')
                ]),
                'fontdata' => $fontData + [
                    'custom_bold' => [
                        'R' => $pdf_font_bold.'.ttf',
                    ],
                    'custom_regular' => [
                        'R' => $pdf_font_regular.'.ttf',
                    ],
                ]
            ]);
        }

        $path_css = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                             DIRECTORY_SEPARATOR.'pdf'.
                                             DIRECTORY_SEPARATOR.$base_template.
                                             DIRECTORY_SEPARATOR.'style.css');

        $stylesheet = file_get_contents($path_css);

        $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        if ($format_pdf != 'ticket') {
            if(config('tenant.pdf_template_footer')) {
                $html_footer = $template->pdfFooter($base_template);
                $pdf->SetHTMLFooter($html_footer);
            }
        }

        $this->uploadFile($filename, $pdf->output('', 'S'), $folder);
    }

    
    public function toPrintFile($model, $folder, $external_id, $format) {

        $record = $model::where('external_id', $external_id)->first();
        if (!$record) throw new Exception("El código {$external_id} es inválido, no se encontro el archivo relacionado");
        $this->reloadPDF($record, $format, $record->filename);
        $temp = tempnam(sys_get_temp_dir(), $folder);
        file_put_contents($temp, $this->getStorage($record->filename, $folder));
        return response()->file($temp);

    }

    private function reloadFile($record, $format, $folder) {
        $this->createPdf($record, $format, $folder);
    }
    

    public function downloadFile($model, $folder, $external_id, $format = 'a4') {

        $record = $model::where('external_id', $external_id)->first();
        if (!$record) throw new Exception("El código {$external_id} es inválido, no se encontro el archivo relacionado");

        return $this->downloadStorage($record->filename, $folder);
    }


    public function uploadFile($filename, $file_content, $file_type) {
        $this->uploadStorage($filename, $file_content, $file_type);
    }


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
