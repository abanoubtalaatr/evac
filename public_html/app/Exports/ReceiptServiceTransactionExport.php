<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReceiptServiceTransactionExport implements FromCollection, WithMapping, WithHeadings
{
    protected $serviceTransaction;

    public function __construct($serviceTransaction)
    {
        $this->serviceTransaction = $serviceTransaction;
    }

    public function collection()
    {
        return collect([$this->serviceTransaction]);
    }

    public function map($serviceTransaction): array
    {
        return [
            $serviceTransaction->service->name,
            $serviceTransaction->agent? $serviceTransaction->agent->name:"",
            $serviceTransaction->passport_no,
            $serviceTransaction->name ,
            $serviceTransaction->surname,
            Carbon::parse($serviceTransaction->created_at)->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return [
            'Service',
            'Agent',
            'Passport no',
            'Name',
            'Surname',
            'Created at',
        ];
    }
}
