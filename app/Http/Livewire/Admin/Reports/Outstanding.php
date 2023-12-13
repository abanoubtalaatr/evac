<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Exports\Reports\AgentApplicationExport;
use App\Mail\AgentApplicationsMail;
use App\Mail\Reports\DirectSalesMail;
use App\Models\Agent;
use App\Models\Application;
use App\Models\ServiceTransaction;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Outstanding extends Component
{
    public $page_title,
        $from,
        $to,
        $visaType,
        $status,
        $agent_id,
        $email,
        $showSendEmail = false,
        $showSendEmailButton= false,
        $message= null, $agent, $isDirect=false;

    public function mount()
    {
        $this->page_title = __('admin.outstanding');
    }

    public function updatedAgentId()
    {
        if(!is_null($this->agent)) {
            $this->showSendEmailButton = true;
        }else{
            $this->showSendEmailButton = false;
        }
    }
    public function toggleShowModal()
    {
        $this->email = null;
        $this->showSendEmail = !$this->showSendEmail;
        $this->message = null;
    }

    public function setAgentToNull()
    {
        $this->agent = null;
        $this->isDirect = !$this->isDirect;
        $this->emit('agentSetToNull');
    }

    public function printData()
    {
        $url = route('admin.report.print.direct_sales', ['fromDate' => $this->from,'toDate' => $this->to]);
        $this->emit('printTable', $url);
    }


    public function updatedAgent()
    {
        $this->isDirect = !$this->isDirect;
    }

    public function getRecords()
    {
        $fromDate = $this->from;
        $toDate = $this->to;

        if (\App\Helpers\isOwner()) {
            $agents = Agent::query();
        } else {
            $agents = Agent::owner();
        }

        $totalSalesByAgent = [];
        $totalSalesForAllAgent = 0;
        $totalUnPaidBal = 0;

        if(!is_null($fromDate) && !is_null($toDate)) {
            foreach ($agents->get() as $agent) {
                // Sum for applications
                $paidBal = $agent->paymentTransactions->sum('amount');

                $applicationSum = $agent->applications()
                        ->whereBetween('created_at', [$fromDate, $toDate])
                        ->sum('vat') +
                    $agent->applications()
                        ->whereBetween('created_at', [$fromDate, $toDate])
                        ->sum('dubai_fee') +
                    $agent->applications()
                        ->whereBetween('created_at', [$fromDate, $toDate])
                        ->sum('service_fee');

                // Sum for serviceTransactions
                $serviceTransactionSum = $agent->serviceTransactions()
                        ->whereBetween('created_at', [$fromDate, $toDate])
                        ->sum('vat') +
                    $agent->serviceTransactions()
                        ->whereBetween('created_at', [$fromDate, $toDate])
                        ->sum('dubai_fee') +
                    $agent->serviceTransactions()
                        ->whereBetween('created_at', [$fromDate, $toDate])
                        ->sum('service_fee');

                $totalSales = $applicationSum + $serviceTransactionSum;
                $totalSalesForAllAgent += $totalSales;
                $totalUnPaidBal += $paidBal;

                $totalSalesByAgent[] = [
                    'agent_id' => $agent->id,
                    'agent_name' => $agent->name,
                    'total_sales' => $totalSales,
                    'un_paid_bail' => $totalSales - $paidBal,
                ];

            }

            $applications = Application::query()->whereNull('travel_agent_id')->get();

            foreach ($applications as $application){
                $data['directs'][] = [
                    'name' => $application->first_name . ' '. $application->last_name,
                    'total' => $application->dubai_fee + $application->service_fee + $application->vat,

                ];
            }
        }else{
            return [];
        }

        $data['agents'] = $totalSalesByAgent;
        $data['total_sales_for_all_agents'] = $totalSalesForAllAgent;
        $data['total_un_paid_bal_for_agents'] = $totalUnPaidBal;
        return $data;
    }

    public function sendEmail(Request $request)
    {
        $this->validate();

        $request->merge([
            'fromDate' => $this->from,
            'toDate' => $this->to,
        ]);

        Mail::to($this->email)->send(new DirectSalesMail( $this->from, $this->to));
        $this->email = null;

        return redirect()->to(route('admin.report.direct_sales'));
    }


    public function exportReport()
    {
        $fileExport = (new \App\Exports\Reports\DirectSalesExport($this->getRecords()));
        return Excel::download($fileExport, 'sales.csv');
    }

    public function getRules()
    {
        return [
            'email' => ['required', 'email']
        ];
    }

    public function resetData()
    {
        return redirect()->to(route('admin.report.direct_sales'));
    }

    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.reports.outstanding', compact('records'))->layout('layouts.admin');;
    }
}
