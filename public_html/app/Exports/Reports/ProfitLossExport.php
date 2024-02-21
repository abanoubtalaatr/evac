<?php

namespace App\Exports\Reports;

use App\Models\Agent;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfitLossExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $dataRows[] = [
            'Description' => "Total sales",
            'Amount' => $this->data['total_sales'],
        ];
        $dataRows[] = [
            'Description' => "Less dubai fee",
            'Amount' => $this->data['dubai_fee'],
        ];


        $dataRows[] = [
            'Description' => "P & L",
            'Amount' => $this->data['profit_loss'],
        ];

        $dataRows[] = [
            'Description' => "Payments Received *",
            'Amount' => $this->data['payments_received'],
        ];

        return collect($dataRows);
    }

    public function headings(): array
    {
        return [

            'Description',
            'Amount',
        ];
    }

    public function styles(Worksheet $sheet)
    {
          }

}
