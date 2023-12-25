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

class AgentStatementExport implements FromCollection
{
    protected $data;
    protected $agent = null;

    public function __construct($data, $agent=null)
    {
        $this->data = $data;
        if($agent){
            $this->agent = Agent::query()->find($agent);
        }
    }

    public function collection()
    {
        $dataRows = [];

        $rowCount =1;
        $totalCrCount = 0;
        $totalDrCount = 0;

        $dataRows = $this->heading();
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

    public function heading()
    {
        $dataRows[] = [
            'Date' => "EVAC",
            'Description' => "",
            'Dr' => "",
            'Cr' => "",
        ];
        for ($i = 0 ; $i< 1; $i++){
            $dataRows[] = [
                'Date' => "",
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
        }

        $dataRows[] = [
            'Date' => "Diyarna Center - Zekrit - Lebanon",
            'Description' => "",
            'Dr' => "",
            'Cr' => "",
        ];
        for ($i = 0 ; $i< 1; $i++){
            $dataRows[] = [
                'Date' => "",
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
        }

        $settings = Setting::query()->first();

        $dataRows[] = [
            'Date' => "Reg No : " . $settings->registration_no,
            'Description' => "",
            'Dr' => "",
            'Cr' => "",
        ];
        for ($i = 0 ; $i< 1; $i++){
            $dataRows[] = [
                'Date' => "",
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
        }
        $dataRows[] = [
            'Date' => "Tel : " . $settings->mobile,
            'Description' => "",
            'Dr' => "",
            'Cr' => "",
        ];
        for ($i = 0 ; $i< 1; $i++){
            $dataRows[] = [
                'Date' => "",
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
        }

        if($this->agent){
            $dataRows[] = [
                'Date' => "Agent : " . $this->agent->name,
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Dr' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Agent Address : " . $this->agent->address,
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Dr' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Financial No : " . $this->agent->financial_no,
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Dr' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Tel : " . $this->agent->telephone,
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Dr' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Agent statement",
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];

            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Dr' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Account no : ". $this->agent->account_no,
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Dr' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Date : " . Carbon::parse(now())->format('Y-m-d'),
                'Description' => "",
                'Dr' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Dr' => "",
                    'Cr' => "",
                ];
            }

        }
        $dataRows[] = [
            'Date' => "Date",
            'Description' => "Description",
            'Dr' => "Dr",
            'Cr' => "Cr",
        ];
        return $dataRows;
    }

}
