<?php

namespace App\Exports\Reports;

use App\Models\Agent;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AgentApplicationExport implements FromCollection, ShouldAutoSize
{
    protected $data;
    protected $agent = null;
    protected $fromDate =null;
    protected  $toDate  = null;

    public function __construct($data, $agent = null, $from, $to)
    {
        $this->data = $data;
        if($agent){
            $this->agent = Agent::query()->find($agent);
        }
        $this->fromDate = $from;
        $this->toDate = $to;
    }

    public function collection()
    {
        $dataRows = [];
        $dataRows = $this->heading();

        $dataRows [] = [
            "ID" => "ID",
            'Description' => 'Description',
            'Type' => 'Type',
            'Date' => 'Date',
        ];

        $count =1;
        foreach ($this->data['applications'] as $application) {
            $dataRows[] = [
                'ID' => $count++,
                'Description' => $application->application_ref .' '.  $application->first_name . ' ' . $application->last_name,
                'Type' => $application->visaType->name,
                'Date' => Carbon::parse($application->created_at)->format('Y-m-d'),
            ];
        }
        foreach ($this->data['serviceTransactions'] as $serviceTransaction) {
            $dataRows[] = [
                'ID' => $count++,
                'Description' =>$serviceTransaction->service_ref . ' - '.  $serviceTransaction->name .' '.  $serviceTransaction->surname,
                'Type' => $serviceTransaction->service->name,
                'Date' => Carbon::parse($serviceTransaction->created_at)->format('Y-m-d'),
            ];
        }

        return collect($dataRows);
    }

    public function map($row): array
    {
        return [
            $row['ID'],
            $row['Description'],
            $row['Type'],
            $row['Date'],
        ];
    }

//    public function headings(): array
//    {
//        return [
//            'ID',
//            'Description',
//            "Type",
//            'Date',
//        ];
//    }

    public function heading()
    {
        $dataRows[] = [
            "ID" => "EVAC",
            "Description"  => "",
            "Type" => "",
            'Date' => "",
        ];
//
        $settings = Setting::query()->first();

//        for ($i = 0 ; $i< 1; $i++){
//            $dataRows[] = [
//                "ID" => "",
//                "Description"  => "",
//                "Type" => "",
//                'Date' => "",
//            ];
//        }

        if($this->fromDate && $this->toDate){
            $dataRows[] = [
                "ID" =>  "From : " . $this->fromDate,
                "Description"  => "To : " . $this->toDate,
                "Type" => "",
                'Date' => "",
            ];

        }

        if($this->agent){
            $dataRows[] = [
                "ID" =>  "Agent : " . $this->agent->name,
                "Description"  => "",
                "Type" => "",
                'Date' => "",
            ];

            $dataRows[] = [
                "ID" =>  "Agent applications ",
                "Description"  => "",
                "Type" => "",
                'Date' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    "ID" => "",
                    "Description"  => "",
                    "Type" => "",
                    'Date' => "",
                ];
            }
        }
        return $dataRows;
    }
}
