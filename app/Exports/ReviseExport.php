<?php

namespace App\Exports;

use App\Models\Agent;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReviseExport implements FromCollection, ShouldAutoSize
{
    protected $data;
    protected $agent = null;
    protected $fromDate =null;
    protected  $toDate  = null;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $dataRows = [];
        $dataRows = $this->heading();

        $dataRows [] = [
            "ID" => "ID",
            'Date' => 'Date',
            'REF' => 'REF',
            'NAME' => 'NAME',
            "Type" => 'Type'
        ];

        $count =1;
        foreach ($this->data as $key =>  $visa) {
            foreach ($visa as $application){
                $dataRows[] = [
                    'ID' => $count++,
                    'Date' => Carbon::parse($application->created_at)->format('Y-m-d'),
                    'REF' => $application->application_ref ,
                    "NAME" => strtoupper($application->first_name . ' ' . $application->last_name),
                    'Type' => $application->visaType->name,
                ];
            }
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
