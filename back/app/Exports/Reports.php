<?php

namespace App\Exports;

use App\Http\Controllers\ReportsCRMController;
use App\Models\ContactCRM;
use App\Models\reportsCRM;
use Illuminate\Contracts\View\View;

use App\Http\Controllers\GeneralFunctionsModuleCRMController;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Log;

ini_set('memory_limit', '1024M');
class Reports implements WithTitle, FromView, WithEvents
{
    use Exportable;

    protected $idReport;

    public function __construct(string $id)
    {
        $this->idReport = $id;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:G100')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            },
        ];
    }

    public function view(): View
    {
        try {
            $reportConfig = reportsCRM::where('_id', $this->idReport)->get();
            $getNameModule = new GeneralFunctionsModuleCRMController();
            $nameFounded = $getNameModule->getRealNameModule($reportConfig[0]->moduloSeleccionado);
    
            $collection = DB::connection('mongodb')
                ->collection($nameFounded)->whereBetween(
                    $reportConfig[0]->campoFecha,
                    [
                        new \MongoDB\BSON\UTCDateTime(strtotime($reportConfig[0]->fechaSeleccionada[0]) * 1000),
                        new \MongoDB\BSON\UTCDateTime(strtotime($reportConfig[0]->fechaSeleccionada[1]) * 1000)
                    ]
                )->orWhereBetween(
                    'wolkvox_fecha_creacion',
                    [
                        new \MongoDB\BSON\UTCDateTime(strtotime($reportConfig[0]->fechaSeleccionada[0]) * 1000),
                        new \MongoDB\BSON\UTCDateTime(strtotime($reportConfig[0]->fechaSeleccionada[1]) * 1000)
                    ]
                )->limit(10000)->get();
            
            return view('reports', [
                "headers" => $reportConfig[0]->camposSeleccionados,
                "documents" => $collection
            ]);
        } catch (Exception $th) {
            Log::info($th);
        }
        
    }


    public function title(): string
    {
        return 'Work Order';
    }
}
