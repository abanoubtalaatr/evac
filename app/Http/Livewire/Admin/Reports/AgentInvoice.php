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
use Carbon\Carbon;
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
        $this->from = Carbon::now()->startOfWeek()->next(Carbon::FRIDAY)->format('Y-m-d');
        $this->to = Carbon::parse($this->from)->addDays(6)->format('Y-m-d');
    }

    public function updatedAgentId()
    {
        if(!is_null($this->agent)) {
            $this->showSendEmailButton = true;
        }else{
            $this->showSendEmailButton = false;
        }
    }

    public function nextWeek()
    {
        $this->from = Carbon::parse($this->from)->addDays(7)->format('Y-m-d');
        $this->to = Carbon::parse($this->to)->addDays(7)->format('Y-m-d');

    }
    public function previousWeek()
    {
        $this->from = Carbon::parse($this->from)->subDays(7)->format('Y-m-d');
        $this->to = Carbon::parse($this->to)->subDays(7)->format('Y-m-d');
    }

    public function toggleShowModal($agent=null)
    {
        $this->email = null;
        $this->showSendEmail = !$this->showSendEmail;
        $this->agentEmailed = $agent;
    }


    public function sendForAdmins(Request $request)
    {
        $this->disableSendForAdminsButton = true;
        $settings = Setting::query()->first();
        $emails = explode(',', $settings->email);

        $data = (new AgentInvoiceService())->getRecords(null, $this->from, $this->to);
        foreach ($emails as $email){
            foreach ($data['agents'] as $agent){
                if(!is_null($agent['agent'])){
                    $request->merge([
                        'agent' => $agent['agent']['id'],
                        'fromDate' => $this->from,
                        'toDate' => $this->to,
                    ]);

                    Mail::to($email)->send(new AgentInvoiceMail($agent['agent']['id'], $this->from, $this->to));
                }
            }

        }
        $this->disableSendForAdminsButton= false;

//        session()->flash('success',"Send Successfully");
//
//        return redirect()->to(route('admin.report.agent_invoices'));
    }
    public function saveInvoices()
    {

        $data = (new AgentInvoiceService())->getRecords(null, $this->from, $this->to);
        $fromDate = '1970-01-01';

        foreach ($data['agents'] as $row) {
            $totalFromVisas = 0;
            $totalFromServices = 0;
            if (!is_null($row['agent'])) {
                $settings = Setting::query()->first();

                $totalAmountFromDayOneUntilEndOfInvoice = (new AgentInvoiceService())->getAgentData($row['agent']['id'], $fromDate, $this->to);

                $allAmountFromDayOneUntilEndOfInvoice = PaymentTransaction::query()
                    ->where('agent_id', $row['agent']['id'])
                    ->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $this->to)
                    ->sum('amount');

                $totalForInvoice = 0;

                foreach ($totalAmountFromDayOneUntilEndOfInvoice['visas'] as $visa) {
                    $totalForInvoice += $visa->totalAmount;
                }
                foreach ($totalAmountFromDayOneUntilEndOfInvoice['services'] as $service) {
                    $totalForInvoice += $service->totalAmount;
                }

                $paymentForAgent = PaymentTransaction::query()
                    ->where('agent_id', $row['agent']['id'])
                    ->when($this->from && $this->to, function ($query) {
                        return $query->whereDate('created_at', '>=', $this->from)
                            ->whereDate('created_at', '<=', $this->to);
                    })
                    ->sum('amount');

                foreach ($row['visas'] as $visa) {
                    $totalFromVisas += $visa->totalAmount;
                }

                foreach ($row['services'] as $service) {
                    $totalFromServices += $service->totalAmount;
                }

                $oldBalance = $totalForInvoice - $allAmountFromDayOneUntilEndOfInvoice - ($totalFromServices + $totalFromVisas);
                if ($oldBalance < 0) {
                    $oldBalance = -$oldBalance;
                }

                // Check if it's a new year and reset the invoice number

                // Check if there are existing records for the agent in agent_invoices table
                $existingRecordsCount = \App\Models\AgentInvoice::query()
                    ->count();

                $lastRow = \App\Models\AgentInvoice::query()->orderBy('invoice_title','desc')->latest()->first();

                if ($lastRow) {
                    $lastTwoDigitsOfYear = intval(trim(substr($lastRow->invoice_title, 4, 3)));
                    $nextInvoiceNumber = intval(trim(substr($lastRow->invoice_title, 10, 3))) + 1;


                    if ($settings->is_new_year == 1) {
                        $nextInvoiceNumber = 1;
                        if ($settings->invoice_start) {
                            $nextInvoiceNumber = intval($settings->invoice_start) +1;
                        }
                    }
                } else {
                    $nextInvoiceNumber = 1;
                    $lastTwoDigitsOfYear = substr(date('y'), -2);

                    if ($settings->is_new_year == 1) {
                        $lastTwoDigitsOfYear = substr(date('y'), -2) ;

                        if ($settings->invoice_start) {
                            $nextInvoiceNumber = intval($settings->invoice_start);
                        }
                    }
                }

                $settings->update(['is_new_year' => 0]);
                // Generate the invoice title with leading zeros
                $invoiceTitle = 'EV / ' . $lastTwoDigitsOfYear . ' / ' . str_pad($nextInvoiceNumber, 3, '0', STR_PAD_LEFT);

                $rawExistBeforeForAgent = \App\Models\AgentInvoice::query()
                    ->where('agent_id', $row['agent']['id'])
                    ->whereDate('from', $this->from)
                    ->whereDate('to', $this->to)
                    ->first();

                $totalAmount = $totalFromServices + $totalFromVisas;
                if (!is_null($rawExistBeforeForAgent)) {
                    $rawExistBeforeForAgent->update([
                        'total_amount' => $totalAmount,
                        'payment_received' => $paymentForAgent,
                        'old_balance' => $oldBalance,
                        'grand_total' => $totalAmount + $oldBalance,
                        'invoice_title' => $invoiceTitle, // Update invoice title
                    ]);
                } else {
                    // Create the AgentInvoice record
                    \App\Models\AgentInvoice::query()->create([
                        'agent_id' => $row['agent']['id'],
                        'invoice_title' => $invoiceTitle,
                        'from' => $this->from,
                        'to' => $this->to,
                        'total_amount' => $totalAmount,
                        'payment_received' => $paymentForAgent,
                        'old_balance' => $oldBalance,
                        'grand_total' => $totalAmount + $oldBalance
                    ]);
                }
            }
        }
        $this->showSaveInvoiceMessage = true;
    }

    public function hideSaveInvoiceMessage()
    {
        $this->showSaveInvoiceMessage= false;
    }

    public function endYear()
    {
        $this->from = Carbon::parse($this->from)->startOfWeek(Carbon::THURSDAY)->format('Y-m-d');
        $this->to = Carbon::now()->format('Y-m-d');
    }

    public function startYear()
    {
        $this->from = Carbon::now()->format('Y-m-d');
        $this->to = Carbon::parse($this->from)->addDays(7)->format('Y-m-d');
        $settings = Setting::query()->first();
        $settings->update(['is_new_year' => 1]);
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


    public function exportReport(Request $request,$id)
    {
        $data = (new AgentInvoiceService())->getRecords($id, $this->from, $this->to);

        $request->merge([
            'agent' => $id,
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
