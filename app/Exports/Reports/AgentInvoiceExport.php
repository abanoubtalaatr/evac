<?php

namespace App\Exports\Reports;

use App\Models\Agent;
use App\Models\AgentInvoice;
use App\Models\PaymentTransaction;
use App\Models\Setting;
use App\Services\AgentInvoiceService;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use function App\Helpers\convertNumberToWorldsInUsd;
use function App\Helpers\formatCurrency;
use function App\Helpers\isExistVat;
use function App\Helpers\valueOfVat;

class AgentInvoiceExport implements FromCollection
{
    protected $data;
    protected $agent = null;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $dataRows = [];

        $rowCount = 1;
        $totalAmount = 0;
        $totalVat = 0;
        $subTotal = 0;
        $this->agent = Agent::query()->find($this->data['agents'][0]['agent']['id']);

        $dataRows = $this->heading();

        // Add Visa Data
        if (isset($this->data['agents'][0]['visas'])) {
            foreach ($this->data['agents'][0]['visas'] as $visa) {
                $dataRows[] = [
                    'Item #' => $rowCount++,
                    'Description' => $visa->name,
                    'Qty' => $visa->qty,
                    'Unit price' => '$ ' . formatCurrency($visa->total),
                    'Amount' => '$ ' . formatCurrency($visa->totalAmount),
                ];
                $totalVat += $visa->totalVat;
                $totalAmount += $visa->totalAmount;
                $subTotal += $visa->totalAmount  ;
            }
        }

      
        // Add Service Data
        if (isset($this->data['agents'][0]['services'])) {
            foreach ($this->data['agents'][0]['services'] as $service) {
                
                $dataRows[] = [
                    'Item #' => $rowCount++,
                    'Description' => $service->name,
                    'Qty' => $service->qty,
                    'Unit price' => '$ ' . formatCurrency($service->service_fee + $service->dubai_fee),
                    'Amount' => '$ ' . formatCurrency($service->amount),
                ];
                
                $totalAmount += $service->amount-  $service->serviceVat;
                $subTotal += $service->amount -  $service->serviceVat;
            }
            
        }

        $dataRows[] = [
            'Item #' => '',
            'Description' => "",
            'Qty' => "",
            'Unit price' => "",
            'Amount' => '',
        ];

        // Calculate Old Balance and Grand Total
        $totalForInvoice = 0;
        $allAmountFromDayOneUntilEndOfInvoice = 0;

        foreach ($this->data['agents'] as $agent) {
            if (!is_null($agent['agent'])) {
                $carbonFrom = Carbon::parse(request()->fromDate);
                $carbonFrom->subDay();
                $fromDate = '1970-01-01';

                $totalAmountFromDayOneUntilEndOfInvoice = (new AgentInvoiceService())->getAgentData(
                    $agent['agent']['id'],
                    $fromDate,
                    $carbonFrom->format('Y-m-d')
                );

                $allAmountFromDayOneUntilEndOfInvoice = PaymentTransaction::query()
                    ->where('agent_id', $agent['agent']['id'])
                    ->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', request()->toDate)
                    ->sum('amount');

                foreach ($totalAmountFromDayOneUntilEndOfInvoice['visas'] as $visa) {
                    $totalForInvoice += $visa->totalAmount;
                }
                foreach ($totalAmountFromDayOneUntilEndOfInvoice['services'] as $service) {
                    $totalForInvoice += $service->totalAmount;
                }
            }
        }

        $oldBalance = ($totalForInvoice + $totalAmountFromDayOneUntilEndOfInvoice['totalVat']) - $allAmountFromDayOneUntilEndOfInvoice;

        

        // Add Subtotal, VAT, Total USD, Old Balance, and Grand Total Rows
        $dataRows[] = [
            'Item #' => '',
            'Description' => "Subtotal",
            'Qty' => "",
            'Unit price' => "",
            'Amount' => '$ ' . formatCurrency($subTotal),
        ];

        if (isExistVat()) {
            $dataRows[] = [
                'Item #' => '',
                'Description' => 'Vat ' . valueOfVat() . ' %',
                'Qty' => "",
                'Unit price' => '',
                'Amount' => '$ ' . $this->data['agents'][0]['totalVat'],
            ];
        }

        $dataRows[] = [
            'Item #' => '',
            'Description' => 'Total USD',
            'Qty' => "",
            'Unit price' => '',
            'Amount' => '$ ' . formatCurrency($totalAmount + $this->data['agents'][0]['totalVat'] - $this->data['agents'][0]['serviceVat']),
        ];

        $dataRows[] = [
            'Item #' => '',
            'Description' => 'Old balance',
            'Qty' => "",
            'Unit price' => '',
            'Amount' => '$ ' . formatCurrency($oldBalance),
        ];

        $dataRows[] = [
            'Item #' => '',
            'Description' => 'Grand total',
            'Qty' => "",
            'Unit price' => '',
            'Amount' => '$ ' . formatCurrency($oldBalance + $totalAmount+ $this->data['agents'][0]['totalVat']),
        ];

        // Add Footer Rows
        for ($i = 0; $i < 3; $i++) {
            $dataRows[] = [
                'Item #' => '',
                'Description' => "",
                'Qty' => "",
                'Unit price' => "",
                'Amount' => ""
            ];
        }

        $numberConvert = $oldBalance + $subTotal + $this->data['agents'][0]['totalVat'];
        

        $dataRows[] = [
            'Item #' => "Amount due in words : " . convertNumberToWorldsInUsd(formatCurrency($numberConvert)),
            'Description' => '',
            'Qty' => '',
            'Unit price' => '',
            'Amount' => '',
        ];

        for ($i = 0; $i < 1; $i++) {
            $dataRows[] = [
                'Item #' => '',
                'Description' => "",
                'Qty' => "",
                'Unit price' => "",
                'Amount' => ""
            ];
        }

        $settings = Setting::query()->first();
        $dataRows[] = [
            'Item #' => $settings->invoice_footer,
            'Description' => '',
            'Qty' => '',
            'Unit price' => '',
            'Amount' => '',
        ];

        return collect($dataRows);
    }

    public function heading()
    {
        $dataRows[] = [
            "Item #" => "EAVC",
            "Description" => "",
            "Qty" => "",
            'Unit price' => "",
            'Amount' => ""
        ];

        $dataRows[] = [
            "Item #" => "Diyarna Center - Zekrit - Lebanon",
            "Description" => "",
            "Qty" => "",
            'Unit price' => "",
            'Amount' => ""
        ];

        $settings = Setting::query()->first();

        $dataRows[] = [
            "Item #" => "Reg No : " . $settings->registration_no,
            "Description" => "",
            "Qty" => "",
            'Unit price' => "",
            'Amount' => ""
        ];

        $dataRows[] = [
            "Item #" => "Tel : " . $settings->mobile,
            "Description" => "",
            "Qty" => "",
            'Unit price' => "",
            'Amount' => ""
        ];

        for ($i = 0; $i < 1; $i++) {
            $dataRows[] = [
                "Item #" => "",
                "Description" => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount' => ""
            ];
        }

        if ($this->agent) {
            $dataRows[] = [
                "Item #" => "Agent : " . $this->agent->name,
                "Description" => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount' => ""
            ];
            $dataRows[] = [
                "Item #" => "Agent Address : " . $this->agent->address,
                "Description" => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount' => ""
            ];

            $dataRows[] = [
                "Item #" => "Tel : " . $this->agent->telephone,
                "Description" => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount' => ""
            ];

            for ($i = 0; $i < 1; $i++) {
                $dataRows[] = [
                    "Item #" => "",
                    "Description" => "",
                    "Qty" => "",
                    'Unit price' => "",
                    'Amount' => ""
                ];
            }
            $dataRows[] = [
                "Item #" => "Account No: " . $this->agent->account_number,
                "Description" => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount' => ""
            ];

            $dataRows[] = [
                "Item #" => "Date : " . Carbon::parse(now())->format('Y-m-d'),
                "Description" => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount' => ""
            ];

            $dataRows[] = [
                "Item #" => "From : " . request()->fromDate,
                "Description" => "To : " . request()->toDate,
                "Qty" => "",
                'Unit price' => "",
                'Amount' => ""
            ];
            $dataRows[] = [
                "Item #" => "INV No : " . AgentInvoice::query()->find(request()->invoice)->invoice_title,
                "Description" => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount' => ""
            ];
            for ($i = 0; $i < 1; $i++) {
                $dataRows[] = [
                    "Item #" => "",
                    "Description" => "",
                    "Qty" => "",
                    'Unit price' => "",
                    'Amount' => ""
                ];
            }
        }
        $dataRows[] = [
            "Item #" => "Item #",
            "Description" => "Description",
            "Qty" => "Qty",
            'Unit price' => "Unit price",
            'Amount' => "Amount"
        ];
        return $dataRows;
    }
}