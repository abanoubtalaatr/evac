<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PaymentTransactionExport implements FromCollection, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;

    }

    public function collection()
    {
        $dataRows = [];

        $dataRows [] = [
            "ID" => "ID",
            'Agent' => 'Agent',
            'Total' => "Total",
            'Amount paid' => 'Amount paid',
            'Amount should paid' => 'Amount should paid',
        ];

        $count =1;
        foreach ($this->data as $key =>  $item) {
            $totalAmount = $item->amount + $item->amount_service;

            $dataRows[] = [
                'ID' => $count++,
                'Agent' => $item->name,
                'Total' => $totalAmount? \App\Helpers\formatCurrency($totalAmount):0,
                'Amount paid' => $item->amount_paid? \App\Helpers\formatCurrency($item->amount_paid):0,
                "Amount should paid" => \App\Helpers\formatCurrency($totalAmount - $item->amount_paid),
            ];

        }
        return collect($dataRows);
    }

    public function map($row): array
    {
        return [
            $row['ID'],
            $row['Agent'],
            $row['Total'],
            $row['Amount paid'],
            $row['Amount should paid'],
        ];
    }
}
