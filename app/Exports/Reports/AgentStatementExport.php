<?php

namespace App\Exports\Reports;
use App\Models\Agent;
use App\Models\PaymentTransaction;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use function App\Helpers\convertNumberToWorldsInUsd;

class AgentStatementExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $dataRows = [];

        $rowCount =1;
        $totalCrCount = 0;
        $totalDrCount = 0;
        if(isset($this->data['invoices'])){
            foreach ($this->data['invoices'] as $invoice){
                $totalDrCount += $invoice->total_amount;

                $dataRows[] = [
                    'Date' => Carbon::parse($invoice->created_at)->format('Y-m-d'),
                    'Description' => $invoice->invoice_title,
                    'Dr' => $invoice->total_amount,
                    'Cr' => '',
                ];
            }
        }

        if(isset($this->data['payment_received'])) {

            if($this->data['payment_received']){
                foreach ($this->data['payment_received'] as $payment){
                    $totalCrCount += $payment->amount;

                    $dataRows[] = [
                        'Date' => Carbon::parse($payment->created_at)->format('Y-m-d'),
                        'Description' =>"Payment received",
                        'Dr' => $payment->amount,
                        'Cr' => '',
                    ];
                }
            }
        }
        $dataRows[] = [
            'Date' => '',
            'Description' => '',
            'Dr' => '',
            'Cr' => '',
        ];

        $dataRows[] = [
            'Date' => '',
            'Description' => '',
            'Dr' => '',
            'Cr' => '',
        ];
        $dataRows[] = [
            'Date' => '',
            'Description' => 'Totals',
            'Dr' => $totalDrCount,
            'Cr' => $totalCrCount,
        ];

        $dataRows[] = [
            'Date' => '',
            'Description' => 'Outstanding bal',
            'Dr' => $totalDrCount -  $totalCrCount,
            'Cr' => '',
        ];

        for($i =0 ; $i < 1; $i++){
            $dataRows[] = [
                'Date' => '',
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
        }
        $dataRows[] = [
            'Date' => "Amount due in words : " . convertNumberToWorldsInUsd($totalDrCount) ,
            'Description' => '',
            'Dr' => '',
            'Cr' => '',
        ];


        $settings = Setting::query()->first();
        $dataRows[] = [
            'Date' =>  $settings->invoice_footer,
            'Description' => '',
            'Dr' => '',
            'Cr' => '',
        ];
        return collect($dataRows);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Description',
            'Dr',
            'Cr',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // ... Your existing styles logic ...
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                // Add rows for name and address data above the heading row
                $event->sheet->setCellValue('A1', "Name: EVAC");
                $event->sheet->setCellValue('A2', "Address: Address");
                $event->sheet->mergeCells('A1:E1');
                $event->sheet->mergeCells('A2:E2');
            },
        ];
    }
}
