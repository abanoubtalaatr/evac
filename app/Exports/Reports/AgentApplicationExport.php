<?php

namespace App\Exports\Reports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AgentApplicationExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $dataRows = [];

        foreach ($this->data['applications'] as $application) {
            $dataRows[] = [
                'ID' => $application->id,
                'Description' => $application->application_ref .' '.  $application->first_name . ' ' . $application->last_name,
                'Type' => $application->visaType->name,
                'Date' => Carbon::parse($application->created_at)->format('Y-m-d'),
            ];
        }
        foreach ($this->data['serviceTransactions'] as $serviceTransaction) {
            $dataRows[] = [
                'ID' => $serviceTransaction->id,
                'Description' => $serviceTransaction->name .' '.  $serviceTransaction->surname,
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

    public function headings(): array
    {
        return [
            'ID',
            'Description',
            "Type",
            'Date',
        ];
    }
}
