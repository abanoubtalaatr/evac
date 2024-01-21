<?php

namespace App\Exports\Reports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DirectSalesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $dataRows = [];

        foreach ($this->data['applications']['invoices'] as $application) {
            $dataRows[] = [
                'Date' => Carbon::parse($application->created_at)->format('Y-m-d'),
                'Application name' =>   $application->first_name . ' ' . $application->last_name.  ' - ' . $application->passport_no,
                'Amount' => $application->amount,
                'Payment' => $application->payment_method =='invoice' ?"Unpaid" :"Paid",
                'Type' => $application->visaType->name,

            ];
        }
        foreach ($this->data['serviceTransactions']['invoices'] as $serviceTransaction) {
            $dataRows[] = [
                'Date' => Carbon::parse($serviceTransaction->created_at)->format('Y-m-d'),
                'Application name' =>   $serviceTransaction->name . ' ' . $serviceTransaction->surname .  ' - ' . $serviceTransaction->passport_no,
                'Amount' => $serviceTransaction->amount,
                'Payment' => $serviceTransaction->payment_method =='invoice' ?"Unpaid" :"Paid",
                'Type' => $serviceTransaction->service->name,

            ];
        }

        foreach ($this->data['applications']['cashes'] as $application) {
            $dataRows[] = [
                'Date' => Carbon::parse($application->created_at)->format('Y-m-d'),
                'Application name' =>   $application->first_name . ' ' . $application->last_name .  ' - ' . $application->passport_no,
                'Amount' => $application->amount,
                'Payment' => $application->payment_method,
                'Type' => $application->visaType->name,
            ];
        }
        foreach ($this->data['serviceTransactions']['cashes'] as $serviceTransaction) {
            $dataRows[] = [
                'Date' => Carbon::parse($serviceTransaction->created_at)->format('Y-m-d'),
                'Application name' =>   $serviceTransaction->name . ' ' . $serviceTransaction->surname.  ' - ' . $application->passport_no,
                'Amount' => $serviceTransaction->amount,
                'Payment' => $serviceTransaction->payment_method,
                'Type' => $serviceTransaction->service->name,

            ];
        }


        return collect($dataRows);
    }

    public function map($row): array
    {
        return [
            $row['Date'],
            $row['Application name'],
            $row['Amount'],
            $row['Payment'],
            $row['Type'],
        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'Application name',
            "Amount",
            'Payment',
            'Type',
        ];
    }
}
