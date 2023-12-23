<?php

namespace App\Exports\Reports;
use App\Models\Agent;
use App\Models\PaymentTransaction;
use App\Models\Service;
use App\Models\VisaType;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AgentSalesExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function collection()
    {
        $dataRows = [];
        $totalAmount = 0;
        $defaultVisa = VisaType::where('is_default', 1)->first();
        $totalDefaultVisaCount = 0;

        foreach ($this->data as $record) {
            $totalAmountForAgent = $record->applications_sum_vat
                + $record->applications_sum_dubai_fee
                + $record->applications_sum_service_fee
                + $record->service_transactions_sum_vat
                + $record->service_transactions_sum_dubai_fee
                + $record->service_transactions_sum_service_fee
                + $record->applications_count
                + $record->service_transactions_count;

            $defaultVisaCount = \App\Models\Application::where('visa_type_id', $defaultVisa->id)
                ->where('travel_agent_id', $record->id)
                ->count();

            $totalDefaultVisaCount += $defaultVisaCount;
            $totalAmount += $totalAmountForAgent;

            $visaCounts = [];
            foreach (VisaType::all() as $visa) {
                $visaCountForAgent = \App\Models\Application::where('visa_type_id', $visa->id)
                    ->where('travel_agent_id', $record->id)
                    ->count();
                $visaCounts[] = $visaCountForAgent;
            }

            $serviceCounts = [];
            foreach (Service::all() as $service) {
                $countServiceForAgent = \App\Models\ServiceTransaction::where('service_id', $service->id)
                    ->where('agent_id', $record->id)
                    ->count();
                $serviceCounts[] = $countServiceForAgent;
            }

            $dataRows[] = array_merge([
                'Agent' => $record->name,
                'Default visa' => $defaultVisaCount,
                'Previous Bal' => $record->previous_bal,
                'New Sales' => $totalAmountForAgent - $record->previous_bal,
                'Total Amounts' => $totalAmountForAgent,
            ], $visaCounts, [
                array_sum($visaCounts), // Total Visas
            ], $serviceCounts, [
                array_sum($serviceCounts), // Total Services
            ]);
        }

        // Calculate totals for the last row
        $totalVisaCountForAllAgents = [];
        $totalServiceCountForAllAgents = [];

        foreach (VisaType::all() as $visa) {
            $totalVisaCount = \App\Models\Application::where('visa_type_id', $visa->id)
                ->whereIn('travel_agent_id', $this->data->pluck('id'))
                ->count();
            $totalVisaCountForAllAgents[] = $totalVisaCount;
        }

        foreach (Service::all() as $service) {
            $totalServiceCount = \App\Models\ServiceTransaction::where('service_id', $service->id)
                ->whereIn('agent_id', $this->data->pluck('id'))
                ->count();
            $totalServiceCountForAllAgents[] = $totalServiceCount;
        }

        $dataRows[] = array_merge([
            'Total',
            $totalDefaultVisaCount,
            $this->data->sum('previous_bal'),
            $totalAmount - $this->data->sum('previous_bal'),
            $totalAmount,
        ], $totalVisaCountForAllAgents, [
            array_sum($totalVisaCountForAllAgents), // Total Visas
        ], $totalServiceCountForAllAgents, [
            array_sum($totalServiceCountForAllAgents), // Total Services
        ]);

        return collect($dataRows);
    }

    public function headings(): array
    {
        $visaTypes = VisaType::all()->pluck('name')->toArray();
        $services = Service::all()->pluck('name')->toArray();


        return [
            'Agent',
            'Default visa',
            'Previous Bal',
            'New Sales',
            'Total Amounts',
            ...$visaTypes,
            "Total Visas",
            ...$services,
            'Total Services'
        ];
    }

}
