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

class DirectSale extends Component
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
        $this->page_title = __('admin.direct_sales');
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

        $data['applications']['invoices'] = Application::query()
            ->where('payment_method', 'invoice')
            ->where('travel_agent_id', null)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        $data['applications']['cashes'] = Application::query()
            ->where('payment_method', 'cash')
            ->where('travel_agent_id', null)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        $data['serviceTransactions']['invoices'] = ServiceTransaction::query()
            ->where('agent_id', null)
            ->where('payment_method', 'invoice')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        $data['serviceTransactions']['cashes'] = ServiceTransaction::query()
            ->where('agent_id', null)
            ->where('payment_method', 'cashes')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

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
            Mail::to($email)->send(new DirectSalesMail( $this->from, $this->to));
        }
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
        return view('livewire.admin.reports.direct-sale', compact('records'))->layout('layouts.admin');;
    }
}
