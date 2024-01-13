<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Exports\Reports\AgentApplicationExport;
use App\Mail\AgentApplicationsMail;
use App\Mail\Reports\AgentInvoiceMail;
use App\Mail\Reports\AgentSalesMail;
use App\Models\Agent;
use App\Models\Application;
use App\Models\PaymentTransaction;
use App\Models\Service;
use App\Models\ServiceTransaction;
use App\Models\Setting;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use function App\Helpers\isOwner;

class AgentSales extends Component
{
    public $page_title,
        $visaTypes,
        $from,
        $to,
        $visaType,
        $status,
        $agent_id,
        $email,
        $showSendEmail = false,
        $showSendEmailButton= false,
        $message= null, $agent, $payment_method, $disableSendForAdminsButton= false;

    public function mount()
    {
        $this->page_title = __('admin.agent_sales');
        $this->visaTypes = VisaType::query()->get();
    }

    public function updatedAgentId()
    {
        if(!is_null($this->agent)) {
            $this->showSendEmailButton = true;
        }else{
            $this->showSendEmailButton = false;
        }
    }

    public function toggleShowModal($agent=null)
    {
        $this->email = null;
        $this->showSendEmail = !$this->showSendEmail;
        $this->agent = $agent;
        $this->message = null;
    }


    public function setAgentToNull()
    {
        $this->agent = null;
        $this->emit('agentSetToNull');
    }
    public function printData()
    {
        $fromDate = $this->from;
        $toDate = $this->to;
        $url = route('admin.report.print.agent_sales', ['fromDate' => $fromDate,'toDate' => $toDate]);
        $this->emit('printTable', $url);
    }

    public function getRecords()
    {
        $fromDate = $this->from;
        $toDate = $this->to;

        $today = now()->format('Y-m-d'); // Get the current date in the format 'YYYY-MM-DD'
        $query = Agent::query();
        // Check if the user is the owner
        if (!isOwner()) {
            $query->owner();
        }

        if($fromDate && $toDate){
            $query->where(function ($query) use ($fromDate, $toDate, $today) {
                if ($fromDate && $toDate) {
                    $query->whereHas('applications', function ($appQuery) use ($fromDate, $toDate) {
                        $appQuery->whereDate('created_at', '>=', $fromDate)
                            ->whereDate('created_at', '<=', $toDate);
                    })->orWhereHas('serviceTransactions', function ($transQuery) use ($fromDate, $toDate) {
                        $transQuery->whereDate('created_at', '>=', $fromDate)
                            ->whereDate('created_at', '<=', $toDate);
                    });
                } else {
                    // If no fromDate and toDate, filter records for the last one week
                    $query->whereHas('applications', function ($appQuery) use ($today) {
                        $appQuery->whereBetween('created_at', [now()->subWeek(), $today]);
                    })->orWhereHas('serviceTransactions', function ($transQuery) use ($today) {
                        $transQuery->whereBetween('created_at', [now()->subWeek(), $today]);
                    });
                }
            })
                ->withSum('applications', 'vat')
                ->withSum('applications', 'dubai_fee')
                ->withSum('applications', 'service_fee')
                ->withSum('serviceTransactions', 'vat')
                ->withSum('serviceTransactions', 'dubai_fee')
                ->withSum('serviceTransactions', 'service_fee')
                ->withCount('applications')
                ->withCount('serviceTransactions');

            // Add a new column for previous balance
            $query->addSelect([
                'previous_bal' => PaymentTransaction::query()
                    ->whereColumn('agent_id', 'agents.id')
                    ->selectRaw('COALESCE(SUM(amount), 0)'),


            ]);


            $result = $query->orderBy('name')->get();

            // Retrieve counts from the result
            $totalApplicationsCount = $result->pluck('applications_count')->sum();
            $totalServiceTransactionsCount = $result->pluck('service_transactions_count')->sum();
            $totalPreviousBalSum = $result->pluck('previous_bal')->sum();
            $totalTotalAmountSum = $result->pluck('total_amount')->sum();

            // Add total counts to the result
            $result->total_applications_count = $totalApplicationsCount;
            $result->total_previous_bal_sum = $totalPreviousBalSum;
            $result->total_service_transactions_count = $totalServiceTransactionsCount;
            return $result;
        }

        return [];
    }

    public function sendEmail(Request $request)
    {
        $this->validate();
        $request->merge([
           'fromDate' => $this->from,
           'toDate' => $this->to,
        ]);

        $fileExport = new \App\Exports\Reports\AgentSalesExport($this->getRecords(), $this->from, $this->to);

        $filePath = storage_path('app/reports/agent_sales_export.csv');

        Excel::store($fileExport, $filePath, 'public');

// Generate a public URL for the stored file
        $publicUrl = asset('storage/reports/agent_sales_export.csv');

        $emails = explode(',', $this->email);
        foreach ($emails as $email){
            Mail::to($email)->send(new AgentSalesMail($this->from, $this->to, $publicUrl));
        }
        $this->email = null;
        $this->message = null;
        return redirect()->to(route('admin.report.agent_sales'));
    }


    public function exportReport()
    {
        $fileExport = (new \App\Exports\Reports\AgentSalesExport($this->getRecords(), $this->from, $this->to));

        return Excel::download($fileExport, 'agent_sales.csv');
    }

    public function getRules()
    {
        return [
          'email' => ['required']
        ];
    }

    public function resetData()
    {
        return redirect()->to(route('admin.report.agent_sales'));
    }

    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.reports.agent-sales', compact('records'))->layout('layouts.admin');
    }
}
