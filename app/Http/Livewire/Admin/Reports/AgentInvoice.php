<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Exports\Reports\AgentApplicationExport;
use App\Mail\AgentApplicationsMail;
use App\Mail\Reports\AgentInvoiceMail;
use App\Models\Agent;
use App\Models\Application;
use App\Models\PaymentTransaction;
use App\Models\Service;
use App\Models\ServiceTransaction;
use App\Models\Setting;
use App\Models\VisaType;
use App\Services\AgentInvoiceService;
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
        $agentEmailed = null,
        $message= null, $agent, $payment_method, $disableSendForAdminsButton= false, $showSaveInvoiceMessage=false;

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
        $this->agentEmailed = $agent;
    }


    public function sendForAdmins()
    {
        $this->disableSendForAdminsButton = true;
        $settings = Setting::query()->first();
        $emails = explode(',', $settings->email);
        $data = (new AgentInvoiceService())->getRecords(null, $this->from, $this->to);
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
    public function saveInvoices()
    {
        $data = (new AgentInvoiceService())->getRecords(null,$this->from, $this->to);

        foreach ($data['agents'] as $row) {
            $totalFromVisas = 0;
            $totalFromServices = 0;
            $paymentForAgent = 0;

            if (!is_null($row['agent'])) {
                $paymentForAgent = PaymentTransaction::query()
                    ->where('agent_id', $row['agent']['id'])
                    ->when($this->from && $this->to, function ($query) {
                        return $query->whereBetween('created_at', [$this->from, $this->to]);
                    })
                    ->sum('amount');

                foreach ($row['visas'] as $visa) {
                    $totalFromVisas += $visa->total * $visa->qty;
                }

                foreach ($row['services'] as $service) {
                    $totalFromServices += $service->amount * $service->qty;
                }

                // Check if there are existing records for the agent in agent_invoices table
                $existingRecordsCount = \App\Models\AgentInvoice::query()
                    ->count();

                $lastRow = \App\Models\AgentInvoice::query()->latest()->first();

                $nextInvoiceNumber = $existingRecordsCount + 1;

                $lastTwoDigitsOfYear = substr(date('y'), -2);

                $settings = Setting::query()->first();

                if(!$lastRow){
                    if($settings->invoice_start){
                        $nextInvoiceNumber = $settings->invoice_start;
                    }
                }
                if($lastRow){
                    [$evac_name, $year, $count] = explode('/', $lastRow->invoice_title);
                    if(intval(trim($year)) < intval(date('y'))){
                        $nextInvoiceNumber = 1;
                    }
                }

                // Generate the invoice title with leading zeros
                $invoiceTitle = 'EV / ' . $lastTwoDigitsOfYear . ' / ' . str_pad($nextInvoiceNumber, 3, '0', STR_PAD_LEFT);

                // Create the AgentInvoice record
                \App\Models\AgentInvoice::query()->create([
                    'agent_id' => $row['agent']['id'],
                    'invoice_title' => $invoiceTitle,
                    'total_amount' => $totalFromServices + $totalFromVisas,
                    'payment_received' => $paymentForAgent,
                ]);
            }
        }
        $this->showSaveInvoiceMessage = true;
    }

    public function hideSaveInvoiceMessage()
    {
        $this->showSaveInvoiceMessage= false;
    }
    public function setAgentToNull()
    {
        $this->agent = null;
        $this->emit('agentSetToNull');
    }
    public function printData($agentId)
    {
        $this->agentEmailed = $agentId;
        $url = route('admin.report.print.agent_invoices', ['agent' => $this->agentEmailed,'fromDate' => $this->from,'toDate' => $this->to]);
        $this->emit('printTable', $url);
    }

    public function getRecords($export= false, $agent = null, $from = null, $to = null)
    {
        if(!$this->agent && !$this->from && !$this->to){
            return [];
        }
        if($export) {
            $agentData = (new AgentInvoiceService())->getAgentData($agent, $from, $to);
            $agentData['agent'] = Agent::query()->find($this->agent); // Add agent_id to the data
            $data['agents'][] = $agentData;
            return $data;
        }
        return (new AgentInvoiceService())->getRecords($agent, $from,$to);
//            if ($this->agent && !is_null($this->agent) && $this->agent !='no_result') {
//                $agentData = $this->getAgentData($this->agent, $this->from, $this->to);
//                $agentData['agent'] = Agent::query()->find($this->agent); // Add agent_id to the data
//                $data['agents'][] = $agentData;
//                return $data;
//            } else{
//                // Display data for all agents with applications this week or service transactions
//                return  $this->getAllAgentsData($this->from, $this->to);
//
//            }

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

        if(is_null($this->agentEmailed) || $this->agentEmailed =='no_result') {
            $this->message = "You must choose travel agent";
            return;
        }

        $agent = Agent::query()->find($this->agentEmailed);

        $request->merge([
           'agent' => $this->agentEmailed,
           'fromDate' => $this->from,
           'toDate' => $this->to,
        ]);


        $emails = explode(',', $this->email);
        foreach ($emails as $email){
            Mail::to($email)->send(new AgentInvoiceMail($agent, $this->from, $this->to));
        }
        $this->toggleShowModal();

//        $this->agent = null;
//        return redirect()->to(route('admin.report.agent_invoices'));
    }


    public function exportReport(Request $request,$parameters)
    {
        list($agentId, $fromDate, $toDate) = explode(',', $parameters);

        $data = (new AgentInvoiceService())->getRecords($agentId, $fromDate, $toDate);

        $request->merge([
            'agent' => $agentId,
            'fromDate' => $this->from,
            'toDate' => $this->to,
        ]);

        $fileExport = (new \App\Exports\Reports\AgentInvoiceExport($data));
        return Excel::download($fileExport, 'agent_invoice.csv');
    }

    public function getRules()
    {
        return [
          'email' => ['required']
        ];
    }

    public function resetData()
    {
        return redirect()->to(route('admin.report.agent_invoices'));
    }

    public function render()
    {
        $records = $this->getRecords(false,$this->agent, $this->from, $this->to);
        return view('livewire.admin.reports.agent-invoice', compact('records'))->layout('layouts.admin');
    }
}
