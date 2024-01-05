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

        $rowCount =1;
        $totalAmount = 0;
        $totalPayment  = PaymentTransaction::query()->where('agent_id', $this->data['agents'][0]['agent']['id'])->sum('amount');
        $this->agent = Agent::query()->find($this->data['agents'][0]['agent']['id']);

        $dataRows = $this->heading();


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
        for($i =0 ; $i < 3; $i++){
            $dataRows[] = [

                'Item #' => '',
                'Description' => "",
                'Qty' => "",
                'Unit price' => "",
                'Amount' => ""
            ];
        }

        $dataRows[] = [
          'Item #' => "Amount due in words : " . convertNumberToWorldsInUsd($totalAmount) ,
          'Description' => '',
          'Qty' => '',
          'Unit price' => '',
          'Amount' => '',
        ];

        for($i =0 ; $i < 1; $i++){
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
            'Item #' =>  $settings->invoice_footer,
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
            "Description"  => "",
            "Qty" => "",
            'Unit price' => "",
            'Amount'=>""
        ];

        $dataRows[] = [
            "Item #" => "Diyarna Center - Zekrit - Lebanon",
            "Description"  => "",
            "Qty" => "",
            'Unit price' => "",
            'Amount'=>""
        ];

        $settings = Setting::query()->first();

        $dataRows[] = [
            "Item #" =>"Reg No : " . $settings->registration_no,
            "Description"  => "",
            "Qty" => "",
            'Unit price' => "",
            'Amount'=>""
        ];

        $dataRows[] = [
            "Item #" =>"Tel : " . $settings->mobile,
            "Description"  => "",
            "Qty" => "",
            'Unit price' => "",
            'Amount'=>""
        ];
        for ($i = 0 ; $i< 1; $i++){
            $dataRows[] = [
                "Item #" =>"",
                "Description"  => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount'=>""
            ];
        }

        if($this->agent){
            $dataRows[] = [
                "Item #" => "Agent : " . $this->agent->name,
                "Description"  => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount'=>""
            ];
            $dataRows[] = [
                "Item #" => "Agent Address : " . $this->agent->address,
                "Description"  => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount'=>""
            ];

            $dataRows[] = [
                "Item #" =>  "Tel : " . $this->agent->telephone,
                "Description"  => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount'=>""
            ];

            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    "Item #" =>   "",
                    "Description"  => "",
                    "Qty" => "",
                    'Unit price' => "",
                    'Amount'=>""
                ];
            }
            $dataRows[] = [
                "Item #" =>  "Account No: " . $this->agent->account_number,
                "Description"  => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount'=>""
            ];

            $dataRows[] = [
                "Item #" =>    "Date : " . Carbon::parse(now())->format('Y-m-d'),
                "Description"  => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount'=>""
            ];

            $dataRows[] = [
                "Item #" =>    "From : " . request()->fromDate,
                "Description"  => "To : " . request()->toDate,
                "Qty" => "",
                'Unit price' => "",
                'Amount'=>""
            ];
            $dataRows[] = [
                "Item #" =>    "INV No : " . Carbon::parse(now())->format('Y/m/d'),
                "Description"  => "",
                "Qty" => "",
                'Unit price' => "",
                'Amount'=>""
            ];
            for ($i = 0 ; $i< 1; $i++){
                $dataRows[] = [
                    "Item #" =>   "",
                    "Description"  => "",
                    "Qty" => "",
                    'Unit price' => "",
                    'Amount'=>""
                ];
            }
        }
        $dataRows[] = [
            "Item #" =>    "Item #",
            "Description"  => "Description",
            "Qty" => "Qty",
            'Unit price' => "Unit price",
            'Amount'=> "Amount"
        ];
        return $dataRows;
    }
}
