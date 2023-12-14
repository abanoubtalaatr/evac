<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Exports\Reports\AgentApplicationExport;
use App\Mail\AgentApplicationsMail;
use App\Mail\Reports\DirectSalesMail;
use App\Mail\Reports\ProfitLossMail;
use App\Models\Agent;
use App\Models\Application;
use App\Models\PaymentTransaction;
use App\Models\ServiceTransaction;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ProfitLoss extends Component
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
        $this->page_title = __('admin.report_total_profit');
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
        $url = route('admin.report.print.profit_loss', ['fromDate' => $this->from,'toDate' => $this->to]);
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
        $applicationServiceFees = Application::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('service_fee');
        $applicationDubaiFees = Application::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('dubai_fee');
        $applicationVats = Application::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('vat');
        $serviceTransactionServiceFees = ServiceTransaction::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('service_fee');
        $serviceTransactionDubaiFees = ServiceTransaction::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('dubai_fee');
        $serviceTransactionVats = ServiceTransaction::query()->whereBetween('created_at', [$fromDate, $toDate])->get()->sum('vat');
        $data['total_sales'] = $applicationServiceFees  + $applicationDubaiFees + $applicationVats +  $serviceTransactionServiceFees  + $serviceTransactionDubaiFees + $serviceTransactionVats;
        $data['profit_loss'] = ($applicationServiceFees + $serviceTransactionServiceFees) - ($applicationVats + $serviceTransactionVats);
        $data['vat'] = $applicationVats + $serviceTransactionVats;
        $data['dubai_fee'] = $applicationDubaiFees + $serviceTransactionDubaiFees;
        $data['payments_received'] = PaymentTransaction::query()->whereBetween('created_at', [$fromDate, $toDate])->sum('amount');
        return $data;
    }

    public function sendEmail(Request $request)
    {
        $this->validate();

        $request->merge([
            'fromDate' => $this->from,
            'toDate' => $this->to,
        ]);

        Mail::to($this->email)->send(new ProfitLossMail( $this->from, $this->to));
        $this->email = null;

        return redirect()->to(route('admin.report.total.profit'));
    }


    public function exportReport()
    {
        $fileExport = (new \App\Exports\Reports\ProfitLossExport($this->getRecords()));
        return Excel::download($fileExport, 'profit_loss.csv');
    }

    public function getRules()
    {
        return [
            'email' => ['required', 'email']
        ];
    }

    public function resetData()
    {
        return redirect()->to(route('admin.report.profit'));
    }

    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.reports.profit-loss', compact('records'))->layout('layouts.admin');;
    }
}
