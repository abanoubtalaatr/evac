<?php

namespace App\Exports\Reports;

use App\Models\Agent;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OutstandingExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $dataRows = [];


        $key =0;
        foreach ($this->data['agents'] as  $agent){
            $dataRows[] = [
                '#' => $key+1,
                'Type' => 'Agent',
                'Name' => $agent['agent_name'],
                'Total sales' =>'$ ' . $agent['total_sales'],
                'UnPaid Bal' =>'$ ' . $agent['un_paid_bail']
            ];
        }
        $dataRows[] = [

            '#' => '',
            'Type' => '',
            'Name' => '',
            'Total sales' =>'',
            'UnPaid Bal'  => '',
        ];

        $dataRows[] = [
            '#' => '',
            'Type' => 'Total Sales',
            'Name' => '',
            'Total sales' =>'$ ' . $this->data['total_sales_for_all_agents'],
            'UnPaid Bal'  =>'$ ' . $this->data['total_un_paid_bal_for_agents'],
        ];

        $dataRows[] = [

            '#' => '',
            'Type' => '',
            'Name' => '',
            'Total sales' =>'',
            'UnPaid Bal'  => '',
        ];

        foreach ($this->data['directs'] as  $agent){
            $dataRows[] = [
                '#' => $key+1,
                'Type' => 'Direct',
                'Name' => $agent['name'],
                'Total sales' => '$ ' . $agent['total'],
                'UnPaid Bal' =>'$ ' . $agent['un_paid']
            ];
        }
        $dataRows[] = [

            '#' => '',
            'Type' => '',
            'Name' => '',
            'Total sales' =>'',
            'UnPaid Bal'  => '',
        ];

        $dataRows[] = [
            '#' => '',
            'Type' => 'Total ',
            'Name' => '',
            'Total sales' => '$ ' .$this->data['total_sales_for_direct'],
            'UnPaid Bal'  => '$ ' .$this->data['total_un_paid_bal_for_direct'],
        ];

        for($i = 0 ; $i < 1; $i++) {
            $dataRows[] = [
                '#' => '',
                'Type' => '',
                'Name' => '',
                'Total sales' => '',
                'UnPaid Bal'  => '',
            ];
        }
        $dataRows[] = [
            '#' => '',
            'Type' => 'Total Sales',
            'Name' => '',
            'Total sales' => '$ ' .$this->data['total_sales_for_direct'] + $this->data['total_sales_for_all_agents'],
            'UnPaid Bal'  => '',
        ];

        $dataRows[] = [
            '#' => '',
            'Type' => 'Total unpaid',
            'Name' => '',
            'Total sales' => '$ ' . $this->data['total_un_paid_bal_for_agents'] + $this->data['total_un_paid_bal_for_direct'],
            'UnPaid Bal'  => '',
        ];

        return collect($dataRows);
    }

    public function headings(): array
    {
        return [
            '#',
            'Type',
            'Name',
            'Total sales',
            "UnPaid Bal",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->data['agents']) + 5; // Assuming the 'Total Sales' row is two rows below the last agent row

        $sheet->getStyle("A$lastRow:E$lastRow")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFFF00', // Yellow color
                ],
            ],
        ]);

        // Add the following line to refresh the worksheet after applying styles
        $sheet->getParent()->getActiveSheet()->calculateColumnWidths();
    }

}
