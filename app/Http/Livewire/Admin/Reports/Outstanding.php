<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Exports\Reports\AgentApplicationExport;
use App\Mail\AgentApplicationsMail;
use App\Mail\Reports\DirectSalesMail;
use App\Mail\Reports\OutstandingMail;
use App\Models\Agent;
use App\Models\Application;
use App\Models\ServiceTransaction;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $url = route('admin.report.print.outstanding', ['fromDate' => $this->from,'toDate' => $this->to]);
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
        set_time_limit(300);

        if (\App\Helpers\isOwner()) {
            $agents = Agent::query()->isActive()->orderBy('name');
        } else {
            $agents = Agent::owner()->isActive()->orderBy('name');
        }

        $totalSalesByAgent = [];
        $totalSalesForAllAgent = 0;
        $totalUnPaidBal = 0;

        foreach ($agents->get() as $agent) {
            $paidBal = $agent->paymentTransactions->sum('amount');

            $applicationSum = $agent->applications()
                    ->sum('vat') +
                $agent->applications()
                    ->sum('dubai_fee') +
                $agent->applications()
                    ->sum('service_fee');

            // Sum for serviceTransactions
            $serviceTransactionSum = $agent->serviceTransactions()->where('status', '!=', 'deleted')
                    ->sum('vat') +
                $agent->serviceTransactions()->where('status', '!=', 'deleted')
                    ->sum('dubai_fee') +
                $agent->serviceTransactions()->where('status', '!=', 'deleted')
                    ->sum('service_fee');

            $totalSales = $applicationSum + $serviceTransactionSum;

            // Skip agents with total sales equal to 0
            if ($totalSales == 0) {
                continue;
            }

            $totalSalesForAllAgent += $totalSales;
            $totalUnPaidForEveryAgent = $totalSales - $paidBal;

            $totalUnPaidBal += $totalUnPaidForEveryAgent;

            if(($totalSales - $paidBal) > 0){
                $totalSalesByAgent[] = [
                    'agent_id' => $agent->id,
                    'agent_name' => $agent->name,
                    'total_sales' => $totalSales,
                    'un_paid_bail' => $totalSales - $paidBal,
                ];
            }
            
        }
        $dataDirect = DB::table(DB::raw('(SELECT name, surname, vat, service_fee, dubai_fee FROM service_transactions WHERE status != "deleted" AND agent_id IS NULL
                    UNION ALL
                    SELECT first_name AS name, last_name AS surname, vat, service_fee, dubai_fee FROM applications WHERE travel_agent_id IS NULL) AS combined_data'))
            ->select('name', 'surname', DB::raw('SUM(vat + service_fee + dubai_fee) AS total_combined_fee'))
            ->groupBy('name', 'surname')
            ->orderBy('name')
            ->get();

        $totalDirectSales =0;
        $totalUnPaidBalDirect =0;
        foreach ($dataDirect as $item) {

            $unpaidAmount = Application::where('first_name', $item->name)
                ->where('last_name', $item->surname)
                ->where('payment_method', 'invoice')
                ->whereNull('travel_agent_id')
                ->sum(DB::raw('service_fee + vat + dubai_fee'));

            $unpaidAmount += ServiceTransaction::where('name', $item->name)
                ->where('surname', $item->surname)
                ->where('status', '!=', 'deleted')
                ->whereNull('agent_id')
                ->where('payment_method', 'invoice')
                ->sum(DB::raw('service_fee + vat + dubai_fee'));

            if($unpaidAmount > 0){
                $data['directs'][] = [
                    'name' => $item->name . ' ' . $item->surname,
                    'total' => $item->total_combined_fee,
                    'un_paid' => $unpaidAmount,
                ];
                    
            }

            $totalDirectSales += $item->total_combined_fee;
            $totalUnPaidBalDirect += $unpaidAmount;
        }

        $data['total_sales_for_direct'] = $totalDirectSales;
        $data['total_un_paid_bal_for_direct'] = $totalUnPaidBalDirect;
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


        $emails = explode(',', $this->email);
        foreach ($emails as $email){
            Mail::to($email)->send(new OutstandingMail( $this->from, $this->to));
        }
        $this->email = null;

        return redirect()->to(route('admin.report.direct_sales'));
    }


    public function exportReport(Request $request)
    {
        $request->merge([
           'fromDate' => $this->from,
           'toDate' => $this->to,
        ]);
        $fileExport = (new \App\Exports\Reports\OutstandingExport($this->getRecords()));
        return Excel::download($fileExport, 'outstanding.csv');
    }

    public function getRules()
    {
        return [
            'email' => ['required']
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
