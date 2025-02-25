<?php

namespace App\Exports\Reports;

use App\Models\Agent;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use function App\Helpers\convertNumberToWorldsInUsd;
use function App\Helpers\formatCurrency;

class AgentStatementExport implements FromCollection, WithHeadings
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

        // Add static header information
        $dataRows[] = ["EVAC", "", "", "", ""];
        $dataRows[] = ["Diyarna Center - Zekrit - Lebanon", "", "", "", ""];
        

        $settings = Setting::query()->first();
        $dataRows[] = ["Reg No: " . $settings->registration_no, "", "", "", ""];
        $dataRows[] = ["Tel: " . $settings->mobile, "", "", "", ""];
        $dataRows[] = ["", "", "", "", ""]; // Empty row for spacing

        if ($this->agent) {
            $dataRows[] = ["Agent: " . $this->agent->name, "", "", "", ""];
            $dataRows[] = ["Agent Address: " . $this->agent->address, "", "", "", ""];
            $dataRows[] = ["Financial No: " . $this->agent->financial_no, "", "", "", ""];
            $dataRows[] = ["Tel: " . $this->agent->telephone, "", "", "", ""];
            $dataRows[] = ["", "", "", "", ""]; // Empty row for spacing
            $dataRows[] = ["Agent Statement", "", "", "", ""];
            $dataRows[] = ["Account No: " . $this->agent->account_no, "", "", "", ""];
            $dataRows[] = ["Date: " . Carbon::parse(now())->format('Y-m-d'), "", "", "", ""];
          
        }

        // Add the main table headings
        $dataRows[] = $this->headings();
        $dataRows[] = ["Inv no", "From", "To", "db", "Cr"]; // Empty row for spacing
        // Add data rows
        $totalDrCount = 0;
        $totalCrCount = 0;

        if (isset($this->data['data'])) {
            foreach ($this->data['data'] as $item) {
                if ($item instanceof \App\Models\AgentInvoice) {
                    $totalDrCount += $item->total_amount;

                    $dataRows[] = [
                        $item->invoice_title,
                        $item->from,
                        $item->to,
                        "$" . formatCurrency($item->total_amount),
                        "",
                    ];
                } else {
                    $totalCrCount += $item->amount;

                    $dataRows[] = [
                        Carbon::parse($item->created_at)->format('Y-m-d'),
                        "",
                        "Payment received" . (!empty($item->note) ? " - Note: $item->note" : ""),
                        "$" . formatCurrency($item->amount),
                    ];
                }
            }
        }


        // Add totals row
        $dataRows[] = [
            "",
            "Totals",
            "",
            "$" . formatCurrency($totalDrCount),
            "$" . formatCurrency($totalCrCount),
        ];

        // Add outstanding balance row
        $dataRows[] = [
            "",
            "Outstanding bal",
            "",
            "$" . formatCurrency($totalDrCount - $totalCrCount),
            "",
        ];

        // Add amount due in words
        $dataRows[] = [
            "Amount due in words: " . convertNumberToWorldsInUsd($totalDrCount - $totalCrCount),
            "",
            "",
            "",
            "",
        ];

        // Add footer from settings
        $dataRows[] = [
            $settings->invoice_footer,
            "",
            "",
            "",
            "",
        ];

        return collect($dataRows);
    }

    public function headings(): array
    {
        return [
            '',
            '',
            '',
            '',
            '',
        ];
    }
}