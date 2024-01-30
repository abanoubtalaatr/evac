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
use function App\Helpers\totalAmount;
use function App\Helpers\totalAmountBetweenTwoDate;

class AgentSalesExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $fromDate;
    protected $toDate;

    public function __construct($data, $fromDate,$toDate)
    {
        $this->data = $data;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }


    public function collection()
    {
        $from = $this->fromDate;
        $to = $this->toDate;

        $dataRows = [];
        $totalDefaultVisaCount = 0;
        $totalAmountForAgent = 0;
        $totalPreviousBalForAllAgents = 0;
        $totalForAllAgent = 0;
        $totalNewSalesForAllAgents = 0;
        $totalVisaCountForDefaultVisa = 0; // New variable to store the total count for the default visa type

        foreach ($this->data as $record) {
            $defaultVisa = VisaType::where('is_default', 1)->first();

            $defaultVisaCount = \App\Models\Application::where('visa_type_id', $defaultVisa->id)
                ->when($from && $to, function ($query) use ($from, $to) {
                    $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
                })
                ->where('travel_agent_id', $record->id)
                ->count();

            $totalDefaultVisaCount += $defaultVisaCount;
            $totalVisaCountForDefaultVisa += $defaultVisaCount; // Increment the total count for the default visa type

            $totalAmountForAgent = \App\Helpers\totalAmount($record->id, $from, $to);
            $totalSalesForApplicationAndServiceTransactions = totalAmountBetweenTwoDate($record->id, $from, $to);
            $totalPreviousBalForAllAgents += \App\Helpers\oldBalance($record->id, $totalAmountForAgent,$from, $to);
            $totalNewSalesForAllAgents += $totalAmountForAgent;
            $totalForAllAgent += \App\Helpers\oldBalance($record->id, $totalAmountForAgent, $from,$to) + $totalAmountForAgent;

            $visaCounts = [];

            foreach (VisaType::where('id', '!=', $defaultVisa->id)->get() as $visa) {
                $visaCountForAgent = \App\Models\Application::where('visa_type_id', $visa->id)
                    ->when($from && $to, function ($query) use ($from, $to) {
                        $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
                    })
                    ->where('travel_agent_id', $record->id)
                    ->count();
                $visaCounts[] = $visaCountForAgent;
            }

            $serviceCounts = [];
            foreach (Service::all() as $service) {
                $countServiceForAgent = \App\Models\ServiceTransaction::where('service_id', $service->id)
                    ->when($from && $to, function ($query) use ($from, $to) {
                        $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
                    })
                    ->where('agent_id', $record->id)
                    ->count();
                $serviceCounts[] = $countServiceForAgent;
            }

            $dataRows[] = array_merge([
                'Agent' => $record->name,
                'Default visa' => $defaultVisaCount,
                'Previous Bal' => \App\Helpers\oldBalance($record->id, $totalAmountForAgent, $from, $to),
                'New Sales' => $totalSalesForApplicationAndServiceTransactions,
                'Total Amounts' => \App\Helpers\oldBalance($record->id, $totalAmountForAgent,$from, $to) + $totalAmountForAgent,
            ], $visaCounts, [
                array_sum($visaCounts) + $defaultVisaCount, // Total Visas
            ], $serviceCounts, [
                array_sum($serviceCounts), // Total Services
            ]);
        }

        // Calculate totals for the last row
        $totalVisaCountForAllAgents = [];
        $totalServiceCountForAllAgents = [];
        $totalVisasCountNumber = 0;

        foreach (VisaType::where('id', '!=', $defaultVisa->id)->get() as $visa) {
            $totalVisaCount = \App\Models\Application::where('visa_type_id', $visa->id)
                ->whereIn('travel_agent_id', $this->data->pluck('id'))
                ->when($from && $to, function ($query) use ($from, $to) {
                    $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
                })
                ->count();
            $totalVisaCountForAllAgents[] = $totalVisaCount;
        }

        $totalDefaultVisaCountForAll = \App\Models\Application::where('visa_type_id', $defaultVisa->id)
            ->whereIn('travel_agent_id', $this->data->pluck('id'))
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            })
            ->count();
        foreach (Service::all() as $service) {
            $totalServiceCount = \App\Models\ServiceTransaction::where('service_id', $service->id)
                ->when($from && $to, function ($query) use ($from, $to) {
                    $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
                })
                ->whereIn('agent_id', $this->data->pluck('id'))
                ->count();
            $totalServiceCountForAllAgents[] = $totalServiceCount;
        }

        $dataRows[] = array_merge([
            'Total',
            $totalDefaultVisaCount,
            $totalPreviousBalForAllAgents,
            $totalNewSalesForAllAgents,
            $totalForAllAgent,
        ], $totalVisaCountForAllAgents, [
            array_sum($totalVisaCountForAllAgents) +$totalDefaultVisaCountForAll, // Total Visas
        ], $totalServiceCountForAllAgents, [
            array_sum($totalServiceCountForAllAgents), // Total Services
        ]);

        return collect($dataRows);
    }

    public function headings(): array
    {
        $defaultVisa = VisaType::where('is_default', 1)->first();

        $visaTypes = VisaType::where('id', '!=', $defaultVisa->id)->get()->pluck('name')->toArray();
        $services = Service::all()->pluck('name')->toArray();


        return [
            'Agent',
            "$defaultVisa->name",
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
