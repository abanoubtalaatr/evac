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
use function App\Helpers\formatCurrency;

class AgentStatementExport implements FromCollection
{
    protected $data;
    protected $agent = null;

    public function __construct($data, $agent = null)
    {
        $this->data = $data;
        if ($agent) {
            $this->agent = Agent::query()->find($agent);
        }
    }

    public function collection()
    {
        $dataRows = [];

        $rowCount = 1;
        $totalCrCount = 0;
        $totalDbCount = 0;

        $dataRows = $this->heading();
        if (isset($this->data['data'])) {
            foreach ($this->data['data'] as $item) {
                if ($item instanceof \App\Models\AgentInvoice) {
                    $totalDbCount += $item->total_amount;

                    $dataRows[] = [
                        'Inv No' => $item->invoice_title,
                        'from' => $item->form,
                        'to' => $item->to,
                        'Db' => "$" . formatCurrency($item->total_amount),
                        'Cr' => '',
                    ];
                } else {
                    $totalCrCount += $item->amount;

                    $dataRows[] = [
                        'Inv No' => Carbon::parse($item->created_at)->format('Y-m-d'),
                        'from' => $item->form,
                        'Description' => "Payment received",
                        'Db' => "$" . formatCurrency($item->amount),
                        'Cr' => '',
                    ];
                }
            }
        }

        $dataRows[] = [
            'Inv No' => '',
            'from' => '',
            'to' => '',
            'Db' => '',
            'Cr' => '',
        ];

        $dataRows[] = [
            'Inv No' => '',
            'from' => '',
            'to' => '',
            'Db' => '',
            'Cr' => '',
        ];
        $dataRows[] = [
            'Inv No' => '',
            'from' => '',
            'to' => 'Totals',
            'Db' => '$' . formatCurrency($totalDbCount),
            'Cr' => "$ " . formatCurrency($totalCrCount),
        ];

        $dataRows[] = [

            'Inv No' => '',
            'from' => '',
            'to' => 'Outstanding bal',
            'Db' => "$ " . formatCurrency($totalDbCount -  $totalCrCount),
            'Cr' => "$ " . '',

        ];

        for ($i = 0; $i < 1; $i++) {
            $dataRows[] = [
                'Inv No' => '',
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
        }
        $dataRows[] = [

            'Inv No' => "Amount due in words : " . convertNumberToWorldsInUsd(formatCurrency($totalDbCount)),
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
        ];


        $settings = Setting::query()->first();
        $dataRows[] = [
            

            'Inv No' => $settings->invoice_footer,
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
        ];
        return collect($dataRows);
    }

    public function headings(): array
    {
        return [
            'Inv No' => '',
            'from' => '',
            'to' => '',
            'Db' => '',
            'Cr' => '',
        ];
    }

    public function heading()
    {
        $dataRows[] = [
            'Inv No' => 'EVAC',
            'from' => '',
            'to' => '',
            'Db' => '',
            'Cr' => '',
        ];
        for ($i = 0; $i < 1; $i++) {
            $dataRows[] = [
                'Inv No' => '',
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
        }

        $dataRows[] = [

            'Inv No' => 'Diyarna Center - Zekrit - Lebanon',
            'from' => '',
            'to' => '',
            'Db' => '',
            'Cr' => '',
        ];
        for ($i = 0; $i < 1; $i++) {
            $dataRows[] = [
                'Inv No' => '',
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
        }

        $settings = Setting::query()->first();

        $dataRows[] = [

            "Reg No : " . $settings->registration_no,
            'from' => '',
            'to' => '',
            'Db' => '',
            'Cr' => '',
        ];
        for ($i = 0; $i < 1; $i++) {
            $dataRows[] = [
                'Inv No' => '',
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
        }
        $dataRows[] = [

            'Inv No' => "Tel : " . $settings->mobile,
            'from' => '',
            'to' => '',
            'Db' => '',
            'Cr' => '',
        ];
        for ($i = 0; $i < 1; $i++) {
            $dataRows[] = [
                'Inv No' => "",
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
        }

        if ($this->agent) {
            $dataRows[] = [

                'Inv No' =>  "Agent : " . $this->agent->name,
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
            for ($i = 0; $i < 1; $i++) {
                $dataRows[] = [
                    'Inv No' =>  "",
                    'from' => '',
                    'to' => '',
                    'Db' => '',
                    'Cr' => '',
                ];
            }
            $dataRows[] = [


                'Inv No' =>  "Agent AdDbess : " . $this->agent->adDbess,
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
            for ($i = 0; $i < 1; $i++) {
                $dataRows[] = [
                    'Inv No' =>  "",
                    'from' => '',
                    'to' => '',
                    'Db' => '',
                    'Cr' => '',
                ];
            }
            $dataRows[] = [


                'Inv No' => "Financial No : " . $this->agent->financial_no,
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
            for ($i = 0; $i < 1; $i++) {
                $dataRows[] = [

                    'Inv No' => "",
                    'from' => '',
                    'to' => '',
                    'Db' => '',
                    'Cr' => '',
                ];
            }
            $dataRows[] = [


                'Inv No' => "Tel : " . $this->agent->telephone,
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
            for ($i = 0; $i < 1; $i++) {
                $dataRows[] = [

                    'Inv No' => "",
                    'from' => '',
                    'to' => '',
                    'Db' => '',
                    'Cr' => '',
                ];
            }
            $dataRows[] = [

                'Inv No' => "Agent statement",
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];

            for ($i = 0; $i < 1; $i++) {
                $dataRows[] = [
                    'Inv No' => "",
                    'from' => '',
                    'to' => '',
                    'Db' => '',
                    'Cr' => '',
                ];
            }
            $dataRows[] = [
                'Inv No' => "Account no : " . $this->agent->account_no,
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
            for ($i = 0; $i < 1; $i++) {
                $dataRows[] = [
                    'Inv No' => "",
                    'from' => '',
                    'to' => '',
                    'Db' => '',
                    'Cr' => '',
                ];
            }
            $dataRows[] = [

                'Inv No' => "Date : " . Carbon::parse(now())->format('Y-m-d'),
                'from' => '',
                'to' => '',
                'Db' => '',
                'Cr' => '',
            ];
            for ($i = 0; $i < 1; $i++) {
                $dataRows[] = [
                    'Inv No' => "",
                    'from' => '',
                    'to' => '',
                    'Db' => '',
                    'Cr' => '',
                ];
            }
        }
        $dataRows[] = [
            'Inv No' => "Date",
            'from' => '',
            'to' => '',
            'Db' => '',
            'Cr' => '',
        ];
        return $dataRows;
    }
}
