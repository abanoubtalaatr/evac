<?php

namespace App\Exports\Reports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DirectSalesExport implements FromCollection, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $dataRows = [];

        $totalInvoices =0;
        $totalCashes = 0;

        $dataRows[] = [
            'Date' => "",
            'Application name' => "Direct Sales Report",
            'Amount' => "",
            'Payment' => "",
            'Type' => "",
        ];
        for ($i= 0 ; $i < 1; $i++) {
            $dataRows[] = [
                'Date' => "",
                'Application name' => "",
                'Amount' => "",
                'Payment' => "",
                'Type' => "",
            ];
        }

            $dataRows[] = [
            'Date' => "Period From ",
            'Application name' =>   request()->fromDate,
            'Amount' => "Till",
            'Payment' => request()->toDate,
            'Type' => "",
        ];

        for ($i= 0 ; $i < 2; $i++){
            $dataRows[] = [
                'Date' => "",
                'Application name' =>  "",
                'Amount' => "",
                'Payment' =>"",
                'Type' => "",
                ];
        }

        $dataRows[] = [
            'Date' => "Date",
            'Application name' =>  "Application name",
            'Amount' => "Amount",
            'Payment' =>"Payment",
            'Type' => "Type",
        ];
        foreach ($this->data['applications']['invoices'] as $application) {
            $totalInvoices +=$application->amount;
            $dataRows[] = [
                'Date' => Carbon::parse($application->created_at)->format('Y-m-d'),
                'Application name' =>   $application->first_name . ' ' . $application->last_name.  ' - ' . $application->passport_no,
                'Amount' => $application->amount,
                'Payment' => $application->payment_method =='invoice' ?"Unpaid" :"Paid",
                'Type' => $application->visaType->name,

            ];
        }
        foreach ($this->data['serviceTransactions']['invoices'] as $serviceTransaction) {
          $totalInvoices += $serviceTransaction->amount;
            $dataRows[] = [
                'Date' => Carbon::parse($serviceTransaction->created_at)->format('Y-m-d'),
                'Application name' =>   $serviceTransaction->name . ' ' . $serviceTransaction->surname .  ' - ' . $serviceTransaction->passport_no,
                'Amount' => $serviceTransaction->amount,
                'Payment' => $serviceTransaction->payment_method =='invoice' ?"Unpaid" :"Paid",
                'Type' => $serviceTransaction->service->name,

            ];
        }
        $dataRows[] = [
            'Date' => "",
            'Application name' =>  "Total Invoices",
            'Amount' => $totalInvoices,
            'Payment' =>"",
            'Type' => "",
        ];


        $dataRows[] = [
            'Date' => "",
            'Application name' =>  "",
            'Amount' => "",
            'Payment' =>"",
            'Type' => "",
        ];

        $dataRows[] = [
            'Date' => "Date",
            'Application name' =>  "Application name",
            'Amount' => "Amount",
            'Payment' =>"Payment",
            'Type' => "Type",
        ];

        foreach ($this->data['applications']['cashes'] as $application) {
          $totalCashes += $application->amount;
            $dataRows[] = [
                'Date' => Carbon::parse($application->created_at)->format('Y-m-d'),
                'Application name' =>   $application->first_name . ' ' . $application->last_name .  ' - ' . $application->passport_no,
                'Amount' => $application->amount,
                'Payment' => $application->payment_method,
                'Type' => $application->visaType->name,
            ];
        }
        foreach ($this->data['serviceTransactions']['cashes'] as $serviceTransaction) {
          $totalCashes += $serviceTransaction->amount;
            $dataRows[] = [
                'Date' => Carbon::parse($serviceTransaction->created_at)->format('Y-m-d'),
                'Application name' =>   $serviceTransaction->name . ' ' . $serviceTransaction->surname.  ' - ' . $application->passport_no,
                'Amount' => $serviceTransaction->amount,
                'Payment' => $serviceTransaction->payment_method,
                'Type' => $serviceTransaction->service->name,

            ];
        }
        $dataRows[] = [
            'Date' => "",
            'Application name' =>  "Total Cash",
            'Amount' => $totalCashes,
            'Payment' =>"",
            'Type' => "",
        ];


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

//    public function headings(): array
//    {
//        return [
//            'Date',
//            'Application name',
//            "Amount",
//            'Payment',
//            'Type',
//        ];
//    }
}
