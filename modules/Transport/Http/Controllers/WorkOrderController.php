<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Person;
use App\Models\Tenant\Establishment;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Company;
use App\Models\Tenant\Warehouse;
use App\Models\Tenant\Configuration;
use Illuminate\Support\Str;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use Exception;
use Modules\Transport\Models\WorkOrder;
use Modules\Transport\Models\Process;
use Modules\Transport\Models\ActivityType;
use Modules\Transport\Http\Resources\WorkOrderCollection;
use Modules\Transport\Http\Resources\WorkOrderResource;
use Modules\Transport\Http\Requests\WorkOrderRequest;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Template;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;


class WorkOrderController extends Controller
{

    use StorageDocument;

    protected $work_order;
    protected $company;

    public function index()
    {
        return view('transport::work-orders.index');
    }

    public function create($id = null)
    {
        return view('transport::work-orders.form', compact('id'));
    }

    public function columns()
    {
        return [
            'number' => 'Número',
            'opening_date' => 'Fecha de apertura',
            'control_number' => 'N° Control',
        ];
    }


    public function records(Request $request)
    {

        $records = WorkOrder::where($request->column, 'like', "%{$request->value}%")
                            ->whereTypeUser()
                            ->latest();

        return new WorkOrderCollection($records->paginate(config('tenant.items_per_page')));
    }
 

    public function searchCustomers(Request $request)
    {

        $customers = Person::where('number','like', "%{$request->input}%")
                            ->orWhere('name','like', "%{$request->input}%")
                            ->whereType('customers')->orderBy('name')
                            ->get()->transform(function($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => $row->number.' - '.$row->name,
                                    'name' => $row->name,
                                    'number' => $row->number,
                                    'identity_document_type_id' => $row->identity_document_type_id,
                                ];
                            });

        return compact('customers');
    }

    public function tables() {

        $customers = $this->table('customers');
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();
        $company = Company::active();
        $processes = Process::get();
        $warehouses = Warehouse::get();
        $activity_types = ActivityType::get();

        return compact('customers', 'establishments','processes', 'warehouses', 'activity_types','company');
    }
 
     
    public function record($id)
    {
        $record = new WorkOrderResource(WorkOrder::findOrFail($id));

        return $record;
    }
 
  
    public function store(WorkOrderRequest $request)
    {

        DB::connection('tenant')->transaction(function () use ($request) {

            $data = $this->mergeData($request);
            $this->work_order =  WorkOrder::updateOrCreate(['id' => $request->input('id')], $data);

            $this->setFilename();
            $this->createPdf($this->work_order,"a4", $this->work_order->filename);

        });

        return [
            'success' => true,
            'data' => [
                'id' => $this->work_order->id,
            ],
        ];

    }

    private function setFilename(){

        $name = [$this->work_order->prefix,$this->work_order->number,date('Ymd')];
        $this->work_order->filename = join('-', $name);
        $this->work_order->save();

    }


    public function mergeData($inputs)
    {

        $this->company = Company::active();

        $values = [
            'user_id' => auth()->id(),
            'external_id' => $inputs['id'] ? $inputs['external_id'] : Str::uuid()->toString(),
            'customer' => PersonInput::set($inputs['customer_id']),
            'establishment' => EstablishmentInput::set($inputs['establishment_id']),
            'soap_type_id' => $this->company->soap_type_id,
            'work_order_state_id' => '01',
            'control_number' => $inputs['id'] ? $inputs['control_number'] : self::newNumber('control_number', $this->company->soap_type_id),
            'number' => $inputs['id'] ? $inputs['number'] : self::newNumber('number', $this->company->soap_type_id),
        ];

        $inputs->merge($values);

        return $inputs->all();
    }


    private static function newNumber($column, $soap_type_id){

        $number = WorkOrder::select($column)
                            ->where('soap_type_id', $soap_type_id)
                            ->max($column);

        return ($number) ? (int)$number + 1 : 1;

    }

    
    public function close($id)
    {

        $obj = WorkOrder::find($id);
        $obj->work_order_state_id = '03';
        $obj->update();

        return [
            'success' => true,
            'message' => 'Órden de trabajo terminada con éxito'
        ];
    }
 

 
    public function table($table)
    {
        switch ($table) {
            case 'customers':

                $customers = Person::whereType('customers')->orderBy('name')->take(20)->get()->transform(function($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number.' - '.$row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'identity_document_type_code' => $row->identity_document_type->code
                    ];
                });
                return $customers;

                break;
            
            default:
                return [];

                break;
        }
    }

    public function searchCustomerById($id)
    {

        $customers = Person::whereType('customers')
                    ->where('id',$id)
                    ->get()->transform(function($row) {
                        return [
                            'id' => $row->id,
                            'description' => $row->number.' - '.$row->name,
                            'name' => $row->name,
                            'number' => $row->number,
                            'identity_document_type_id' => $row->identity_document_type_id,
                        ];
                    });

        return compact('customers');
    }


    public function download($external_id, $format = "a4") {

        $work_order = WorkOrder::where('external_id', $external_id)->first();

        if (!$work_order) throw new Exception("El código {$external_id} es inválido, no se encontro el archivo relacionado");

        $this->reloadPDF($work_order, $format, $work_order->filename);

        return $this->downloadStorage($work_order->filename, 'work_order');

    }

    public function toPrint($external_id, $format) {

        $work_order = WorkOrder::where('external_id', $external_id)->first();

        if (!$work_order) throw new Exception("El código {$external_id} es inválido, no se encontro el archivo relacionado");

        $this->reloadPDF($work_order, $format, $work_order->filename);
        $temp = tempnam(sys_get_temp_dir(), 'work_order');

        file_put_contents($temp, $this->getStorage($work_order->filename, 'work_order'));

        return response()->file($temp);
    }

    private function reloadPDF($work_order, $format, $filename) {
        $this->createPdf($work_order, $format, $filename);
    }


    public function createPdf($work_order = null, $format_pdf = null, $filename = null) {

        ini_set("pcre.backtrack_limit", "5000000");
        $template = new Template();
        $pdf = new Mpdf();

        $document = ($work_order != null) ? $work_order : $this->work_order;
        $company = ($this->company != null) ? $this->company : Company::active();
        $filename = ($filename != null) ? $filename : $this->work_order->filename;

        $base_template = Configuration::first()->formats;

        $html = $template->pdf($base_template, "work_order", $company, $document, $format_pdf);
 
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

        $this->uploadFile($filename, $pdf->output('', 'S'), 'work_order');
    }

    public function uploadFile($filename, $file_content, $file_type) {
        $this->uploadStorage($filename, $file_content, $file_type);
    }

}
