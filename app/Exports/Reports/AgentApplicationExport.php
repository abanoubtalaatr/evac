<?php

namespace App\Exports\Reports;

use App\Models\Agent;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
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
            'Date' => 'Date',
            'REF' => 'REF',
            'PASSPORT NO' => 'PASSPORT NO',
            'NAME' => 'NAME',
            "Type" => 'Type'
        ];

        $count =1;
        foreach ($this->data['applications'] as $application) {
            $dataRows[] = [
                'ID' => $count++,
                'Date' => Carbon::parse($application->created_at)->format('Y-m-d'),
                'REF' => $application->application_ref ,
                'PASSPORT NO' => $application?->passport_no ,
                "NAME" =>   strtoupper($application->first_name . ' ' . $application->last_name),
                'Type' => $application->visaType->name,
            ];
        }
        foreach ($this->data['serviceTransactions'] as $serviceTransaction) {
            $dataRows[] = [
                'ID' => $count++,
                'Date' => Carbon::parse($serviceTransaction->created_at)->format('Y-m-d'),
                'REF' =>$serviceTransaction->service_ref ,
                'PASSPORT NO' => $application?->passport_no ,
                "NAME" => strtoupper($serviceTransaction->name .' '.  $serviceTransaction->surname),
                'Type' => $serviceTransaction->service->name,
            ];
        }

        return collect($dataRows);
    }

    public function map($row): array
    {
        return [
            $row['ID'],
            $row['Date'],
            $row['REF'],
            $row['NAME'],
            $row['Type'],
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
        if($this->agent){
            $dataRows[] = [
                "ID" =>  "Agent applications :" .$this->agent->name,
                "Date"  => "",
                "REF" => "",
                'NAME' => "",
                "Type" => "",
            ];

        }

        if($this->fromDate && $this->toDate){
            $dataRows[] = [
                "ID" =>  "",
                "Date"  => "",
                "REF" => "From : " . $this->fromDate,
                'NAME' => "To : " . $this->toDate,
                "Type" => "",
            ];

        }
        for ($i = 0 ; $i< 1; $i++){
            $dataRows[] = [
                "ID" =>  "",
                "Date"  => "",
                "REF" => "",
                'NAME' => "",
                "Type" => "",
            ];
        }

        return $dataRows;
    }
}
