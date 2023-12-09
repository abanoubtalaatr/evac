<?php

namespace App\Exports\Reports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class AgentApplicationExport implements FromCollection
{
    protected $applications;
    protected $serviceTransactions;

    public function __construct($applications, $serviceTransactions)
    {
        $this->applications = $applications;
        $this->serviceTransactions = $serviceTransactions;
    }

    public function collection()
    {
        return collect([$this->applications, $this->serviceTransactions]);
    }

    public function map($data): array
    {
        if (isset($data['vis_provider_id'])) {
            // Application data
            return [
                $data['id'],
                $data['first_name'] . ' ' . $data['last_name'],
                $data['visaType']['name'],
               Carbon::parse( $data['created_at'])->format('Y-m-d'),
            ];
        }

        return [];
    }

    public function headings(): array
    {
        // Adjust your headings based on your actual data structure
        return [
            'Description',
            'Type',
            'Date',
        ];
    }
}
