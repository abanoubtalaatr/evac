<?php

namespace App\Exports\Reports;
use App\Models\Agent;
use App\Models\PaymentTransaction;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AgentInvoiceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
        $totalAmount = 0;
        $totalPayment  = PaymentTransaction::query()->where('agent_id', $this->data['agents'][0]['agent']['id'])->sum('amount');

        if(isset($this->data['agents'][0]['visas'])){
            foreach ($this->data['agents'][0]['visas'] as $visa) {
                $dataRows[] = [
                    'Item #' => $rowCount++,
                    'Description' => $visa->name,
                    'Qty' => $visa->qty,
                    'Unit price' => $visa->total,
                    'Amount' => $visa->qty * $visa->total,
                ];
            $totalAmount += $visa->qty * $visa->total;
            }
        }

        if(isset($this->data['agents'][0]['services'])){
            foreach ($this->data['agents'][0]['services'] as $service) {
                $dataRows[] = [
                    'Item #' => $rowCount++,
                    'Description' => $service->name,
                    'Qty' => $service->qty,
                    'Unit price' => $service->amount,
                    'Amount' => $service->qty * $service->amount,
                ];
                $totalAmount += $service->qty * $service->amount;
            }
        }


        $dataRows[] = [

            'Item #' => '',
            'Description' => "",
            'Qty' => "",
            'Unit price' => "",
            'Amount' => ""
        ];
        $dataRows[] = [
          'Item #' => '',
          'Description' => "",
          'Qty' => "",
          'Unit price' => 'Total USD',
          'Amount' => $totalAmount
        ];
        $dataRows[] = [
            'Item #' => '',
            'Description' => "",
            'Qty' => "",
            'Unit price' => 'Old balance',
            'Amount' => ($totalAmount + $totalPayment) - $totalAmount
        ];
        $dataRows[] = [
            'Item #' => '',
            'Description' => "",
            'Qty' => "",
            'Unit price' => 'Grand total',
            'Amount' => $totalAmount - $totalPayment
        ];

         return collect($dataRows);
    }

    public function headings(): array
    {
        return [
            'Item #',
            'Description',
            'Qty',
            'Unit price',
            'Amount',
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
