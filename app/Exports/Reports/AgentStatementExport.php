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
        $totalDbCount = 0;

        $dataRows = $this->heading();
        if(isset($this->data['data'])){
            foreach ($this->data['data'] as $item){
                if( $item instanceof \App\Models\AgentInvoice){
                    $totalDbCount += $item->total_amount;

                    $dataRows[] = [
                        'Date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                        'Description' => $item->invoice_title,
                        'Db' => "$" . formatCurrency($item->total_amount),
                        'Cr' => '',
                    ];
                }else{
                    $totalCrCount += $item->amount;

                    $dataRows[] = [
                        'Date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                        'Description' =>"Payment received",
                        'Db' => "$" . formatCurrency($item->amount),
                        'Cr' => '',
                    ];
                }

            }
        }

        $dataRows[] = [
            'Date' => '',
            'Description' => '',
            'Db' => '',
            'Cr' => '',
        ];

        $dataRows[] = [
            'Date' => '',
            'Description' => '',
            'Db' => '',
            'Cr' => '',
        ];
        $dataRows[] = [
            'Date' => '',
            'Description' => 'Totals',
            'Db' => '$' . formatCurrency($totalDbCount),
            'Cr' => "$ " . formatCurrency($totalCrCount),
        ];

        $dataRows[] = [
            'Date' => '',
            'Description' => 'Outstanding bal',
            'Db' => "$ " . formatCurrency($totalDbCount -  $totalCrCount),
            'Cr' => '',
        ];

        for($i =0 ; $i < 1; $i++){
            $dataRows[] = [
                'Date' => '',
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
        }
        $dataRows[] = [
            'Date' => "Amount due in words : " . convertNumberToWorldsInUsd(formatCurrency($totalDbCount)) ,
            'Description' => '',
            'Db' => '',
            'Cr' => '',
        ];


        $settings = Setting::query()->first();
        $dataRows[] = [
            'Date' =>  $settings->invoice_footer,
            'Description' => '',
            'Db' => '',
            'Cr' => '',
        ];
        return collect($dataRows);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Description',
            'Db',
            'Cr',
        ];
    }

    public function heading()
    {
        $dataRows[] = [
            'Date' => "EVAC",
            'Description' => "",
            'Db' => "",
            'Cr' => "",
        ];
        for ($i = 0 ; $i< 1; $i++){
            $dataRows[] = [
                'Date' => "",
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
        }

        $dataRows[] = [
            'Date' => "Diyarna Center - Zekrit - Lebanon",
            'Description' => "",
            'Db' => "",
            'Cr' => "",
        ];
        for ($i = 0 ; $i< 1; $i++){
            $dataRows[] = [
                'Date' => "",
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
        }

        $settings = Setting::query()->first();

        $dataRows[] = [
            'Date' => "Reg No : " . $settings->registration_no,
            'Description' => "",
            'Db' => "",
            'Cr' => "",
        ];
        for ($i = 0 ; $i< 1; $i++){
            $dataRows[] = [
                'Date' => "",
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
        }
        $dataRows[] = [
            'Date' => "Tel : " . $settings->mobile,
            'Description' => "",
            'Db' => "",
            'Cr' => "",
        ];
        for ($i = 0 ; $i< 1; $i++){
            $dataRows[] = [
                'Date' => "",
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
        }

        if($this->agent){
            $dataRows[] = [
                'Date' => "Agent : " . $this->agent->name,
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Db' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Agent AdDbess : " . $this->agent->adDbess,
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Db' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Financial No : " . $this->agent->financial_no,
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Db' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Tel : " . $this->agent->telephone,
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Db' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Agent statement",
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];

            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Db' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Account no : ". $this->agent->account_no,
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Db' => "",
                    'Cr' => "",
                ];
            }
            $dataRows[] = [
                'Date' => "Date : " . Carbon::parse(now())->format('Y-m-d'),
                'Description' => "",
                'Db' => "",
                'Cr' => "",
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    'Date' => "",
                    'Description' => "",
                    'Db' => "",
                    'Cr' => "",
                ];
            }

        }
        $dataRows[] = [
            'Date' => "Date",
            'Description' => "Description",
            'Db' => "Db",
            'Cr' => "Cr",
        ];
        return $dataRows;
    }

}
