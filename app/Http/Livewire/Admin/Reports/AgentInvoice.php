<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Exports\Reports\AgentApplicationExport;
use App\Mail\AgentApplicationsMail;
use App\Mail\Reports\AgentInvoiceMail;
use App\Models\Agent;
use App\Models\Application;
use App\Models\Service;
use App\Models\ServiceTransaction;
use App\Models\Setting;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AgentInvoice extends Component
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
        $this->page_title = __('admin.agent_invoices');
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


    public function sendForAdmins()
    {
        $this->disableSendForAdminsButton = true;
        $settings = Setting::query()->first();
        $emails = explode(',', $settings->email);
        $data = $this->getRecords();
        foreach ($emails as $email){
            foreach ($data['agents'] as $agent){
                if(!is_null($agent['agent'])){
                    Mail::to($email)->send(new AgentInvoiceMail($agent['agent']['id'], $this->from, $this->to));
                }
            }

        }
        $this->disableSendForAdminsButton= false;

        session()->flash('success',"Send Successfully");

        return redirect()->to(route('admin.report.agent_invoices'));
    }

    public function setAgentToNull()
    {
        $this->agent = null;
        $this->emit('agentSetToNull');
    }
    public function printData($parameters)
    {
        list($agentId, $fromDate, $toDate) = explode(',', $parameters);
        $url = route('admin.report.print.agent_invoices', ['agent' => $agentId,'fromDate' => $fromDate,'toDate' => $toDate]);
        $this->emit('printTable', $url);
    }

    public function getRecords($export= false)
    {
        if($export) {

            $agentData = $this->getAgentData($this->agent, $this->from, $this->to);
            $agentData['agent'] = Agent::query()->find($this->agent); // Add agent_id to the data
            $data['agents'][] = $agentData;
            return $data;
        }
            if ($this->agent && !is_null($this->agent) && $this->agent !='no_result') {

                $agentData = $this->getAgentData($this->agent, $this->from, $this->to);
                $agentData['agent'] = Agent::query()->find($this->agent); // Add agent_id to the data
                $data['agents'][] = $agentData;
                return $data;
            } else{
                // Display data for all agents with applications this week or service transactions
                return  $this->getAllAgentsData($this->from, $this->to);

            }

            return [];
    }

    protected function getAgentData($agentId, $from, $to)
    {
        $data = ['visas' => [], 'services' => []];

        $visas = VisaType::query()->get();

        foreach ($visas as $visa) {
            $applications = Application::query()
                ->where('visa_type_id', $visa->id)
                ->where('travel_agent_id', $agentId);

            if ($from && $to) {
                $applications->whereBetween('created_at', [$from, $to . ' 23:59:59']);
            }

            $applications = $applications->get(); // Assign the results to the variable

            $visa->qty = $applications->count();
            $data['visas'][] = $visa;
        }

        $services = Service::query()->get();

        foreach ($services as $service) {
            $serviceTransactions = ServiceTransaction::query()
                ->where('agent_id', $agentId)
                ->where('service_id', $service->id);

            if ($from && $to) {
                $serviceTransactions->whereBetween('created_at', [$from, $to . ' 23:59:59']);
            }

            $serviceTransactions = $serviceTransactions->get(); // Assign the results to the variable

            $service->qty = $serviceTransactions->count();
            $data['services'][] = $service;
        }

        return $data;
    }

    protected function getAllAgentsData($from, $to)
    {
        $data = ['agents' => []];

        // Get all agents with applications this week
        if($from && $to){
            $agentsWithApplications = Application::whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->pluck('travel_agent_id')
                ->unique();

            // Get all agents with service transactions
            $agentsWithServiceTransactions = ServiceTransaction::whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->pluck('agent_id')
                ->unique();
        }else{

            $agentsWithApplications = Application::pluck('travel_agent_id')->unique();

            // Get all agents with service transactions
            $agentsWithServiceTransactions = ServiceTransaction::pluck('agent_id')->unique();
        }

        $allAgents = $agentsWithApplications->merge($agentsWithServiceTransactions)->unique();

        // Fetch data for each agent
        foreach ($allAgents as $agentId) {
            $agentData = $this->getAgentData($agentId, $from, $to);
            $agentData['agent'] = Agent::query()->find($agentId); // Add agent_id to the data
            $data['agents'][] = $agentData;
        }

        return $data;
    }

    public function sendEmail(Request $request)
    {
        $this->validate();

        if(is_null($this->agent) || $this->agent =='no_result') {
            $this->message = "You must choose travel agent";
            return;
        }

        $agent = Agent::query()->find($this->agent);

        $request->merge([
           'agent' => $this->agent,
           'fromDate' => $this->from,
           'toDate' => $this->to,
        ]);

        Mail::to($this->email)->send(new AgentInvoiceMail($agent, $this->from, $this->to));
        $this->email = null;
        $this->message = null;
        $this->agent = null;
        return redirect()->to(route('admin.report.agent_invoices'));
    }


    public function exportReport($parameters)
    {
        list($agentId, $fromDate, $toDate) = explode(',', $parameters);

        $this->agent = $agentId;
        $this->from = $fromDate;
        $this->to = $toDate;
        $fileExport = (new \App\Exports\Reports\AgentInvoiceExport($this->getRecords(true)));
        $this->agent = null;
        $this->from = null;
        $this->to = null;
        return Excel::download($fileExport, 'agent_invoice.csv');
    }

    public function getRules()
    {
        return [
          'email' => ['required', 'email']
        ];
    }

    public function resetData()
    {
        return redirect()->to(route('admin.report.agent_invoices'));
    }

    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.reports.agent-invoice', compact('records'))->layout('layouts.admin');
    }
}
