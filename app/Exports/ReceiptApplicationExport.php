<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReceiptApplicationExport implements FromCollection, WithMapping, WithHeadings
{
    protected $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function collection()
    {
        return collect([$this->application]);
    }

    public function map($application): array
    {
        return [
            $application->passport_no,
            $application->first_name . ' ' . $application->last_name,
            $application->application_ref,
            $application->travelAgent ? $application->travelAgent->name : '',
            $application->visaProvider ? $application->visaProvider->name : '',
            $application->visaType ? $application->visaType->name : '',
            $application->status,
        ];
    }

    public function headings(): array
    {
        return [
            'Passport',
            'Applicant',
            'Application Ref',
            'Travel Agent',
            'Visa Provider',
            'Visa Type',
            'Status',
        ];
    }
}
