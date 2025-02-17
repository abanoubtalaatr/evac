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
        $showSendEmailButton = false,
        $agentEmailed = null,
        $goToNextYear = 0,
        $message = null, $agent, $payment_method, $disableSendForAdminsButton = false, $showSaveInvoiceMessage = false;


    public function mount()
    {
        $this->page_title = __('admin.agent_invoices');
        $this->visaTypes = VisaType::query()->get();

        // Get the previous Friday
        $this->from = Carbon::now()->previous(Carbon::FRIDAY)->format('Y-m-d');

        // Get the following Thursday (6 days after Friday)
        $this->to = Carbon::parse($this->from)->addDays(6)->format('Y-m-d');
    }

    public function updatedAgentId()
    {
        if (!is_null($this->agent)) {
            $this->showSendEmailButton = true;
        } else {
            $this->showSendEmailButton = false;
        }
    }
    public function nextWeek()
    {
        $year = Carbon::parse($this->to)->format('Y');
        $month = Carbon::parse($this->to)->format('m');
        $day = Carbon::parse($this->to)->format('d');
        $endOfYear = ($year . '-' . '12' . '-' . '31');

        if ($this->to == $endOfYear) {
            $this->from = Carbon::createFromDate(Carbon::parse($this->from)->addYear()->year, 1, 1)->format('Y-m-d');
            $this->to = Carbon::parse($this->from)->next(Carbon::THURSDAY)->format('Y-m-d');
        } elseif ($this->isLastThursdayInYear($this->to)) {
            $this->from = Carbon::parse($this->from)->next(Carbon::FRIDAY)->format('Y-m-d');
            $this->to = Carbon::parse($this->to)->endOfYear()->format('Y-m-d');
        } else {
            // Move to the next Friday and Thursday
            $this->from = Carbon::parse($this->from)->next(Carbon::FRIDAY)->format('Y-m-d');
            $this->to = Carbon::parse($this->from)->next(Carbon::THURSDAY)->format('Y-m-d');

            // Check if the current week is the last week of the year
            if (Carbon::parse($this->from)->weekOfYear === Carbon::parse($this->from)->weeksInYear) {
                // Set start date to January 1st of the next year
                $this->from = Carbon::parse($this->from)->addYear()->startOfYear()->next(Carbon::THURSDAY)->format('Y-m-d');
                // Move to the next Thursday
                $this->to = Carbon::parse($this->from)->next(Carbon::THURSDAY)->format('Y-m-d');
            }
        }
    }

    public function isLastThursdayInYear($to)
    {
        $lastDayOfYear = Carbon::parse($to)->endOfYear();

        // Find the last Thursday before the last day of the year
        $lastThursday = $lastDayOfYear->previous(Carbon::THURSDAY);

        // Check if the provided date is the same as the last Thursday
        return $to === $lastThursday->format('Y-m-d');
    }

    public function isFirstFridayOfYear($date)
    {
        $parsedDate = Carbon::parse($date);

        // Check if the date is a Friday and it belongs to the first week of the year
        return $parsedDate->dayOfWeek === Carbon::FRIDAY && $parsedDate->weekOfYear === 1;
    }
    public function previousWeek()
    {
        if ($this->isFirstFridayOfYear($this->from)) {
            $this->from = Carbon::parse($this->from)->startOfYear()->format('Y-m-d');

            // Set $this->to to the first Thursday of the current year
            $this->to = Carbon::parse($this->from)->next(Carbon::THURSDAY)->format('Y-m-d');
        } else {
            // Set $this->from to the previous Friday
            $this->from = Carbon::parse($this->from)->previous(Carbon::FRIDAY)->format('Y-m-d');

            // Set $this->to to the previous Thursday
            $this->to = Carbon::parse($this->to)->previous(Carbon::THURSDAY)->format('Y-m-d');

            // Check if $this->from is in the previous year
            if (Carbon::parse($this->to)->year != Carbon::parse($this->from)->year) {
                // If so, set $this->from to the last day of the previous year
                $this->to = Carbon::parse($this->from)->endOfYear()->format('Y-m-d');
            }

            $isFromLastWeek = Carbon::parse($this->from)->weekOfYear === Carbon::parse($this->from)->endOfYear()->weekOfYear;

            // Check if $this->to is in the last week of the year
            $isToLastWeek = Carbon::parse($this->to)->weekOfYear === Carbon::parse($this->to)->endOfYear()->weekOfYear;

            if ($isFromLastWeek && $isToLastWeek) {
                $this->to = Carbon::parse($this->to)->endOfYear()->format('Y-m-d');
            }
        }
    }


    public function toggleShowModal($agent = null)
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
        foreach ($emails as $email) {
            foreach ($data['agents'] as $agent) {
                if (!is_null($agent['agent'])) {
                    $request->merge([
                        'agent' => $agent['agent']['id'],
                        'fromDate' => $this->from,
                        'toDate' => $this->to,
                    ]);

                    Mail::to($email)->send(new AgentInvoiceMail($agent['agent']['id'], $this->from, $this->to));
                }
            }
        }
        $this->disableSendForAdminsButton = false;

        //        session()->flash('success',"Send Successfully");
        //
        //        return redirect()->to(route('admin.report.agent_invoices'));
    }
    public function saveInvoices($agent = null)
    {

        if ($agent) {
            $data = (new AgentInvoiceService())->getRecords($agent, $this->from, $this->to);
        } else {
            $data = (new AgentInvoiceService())->getRecords(null, $this->from, $this->to);
        }
        
        $fromDate = '1970-01-01';

        foreach ($data['agents'] as $row) {
            
            $totalAmount = 0;
            if (!is_null($row['agent'])) {
                $settings = Setting::query()->first();

                $carbonFrom = Carbon::parse($this->from);
                $carbonFrom->subDay();

                $totalAmountFromDayOneUntilEndOfInvoice = (new AgentInvoiceService())->getAgentData(
                    $row['agent']['id'],
                    $fromDate,
                    $carbonFrom->format('Y-m-d')
                );
                $getTotalAmount = (new AgentInvoiceService())->getAgentData(
                    $row['agent']['id'],
                    $this->from,
                    $this->to
                );

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
                foreach ($getTotalAmount['visas'] as $visa) {

                    $totalAmount += $visa->totalAmount;
                }
                foreach ($getTotalAmount['services'] as $service) {
                    $totalAmount += $service->totalAmount;
                }


                $oldBalance = ($totalForInvoice) - $allAmountFromDayOneUntilEndOfInvoice;

                $year = substr($this->to, 2, 2);
                $twoDigitYear = substr($this->to, 2, 2);

                // Convert the two-digit year to a full four-digit year
                $fourDigitYear = Carbon::createFromFormat('y', $twoDigitYear)->year;

                $lastRow = \App\Models\AgentInvoice::query()
                    ->where('last_valid_invoice', 1)
                    ->whereYear('from', $fourDigitYear)
                    ->orderBy('invoice_title', 'desc')
                    ->latest()
                    ->first();

                if ($lastRow) {

                    $lastTwoDigitsOfYear = intval(trim(substr($lastRow->invoice_title, 4, 3)));
                    $nextInvoiceNumber = intval(trim(substr($lastRow->invoice_title, 10, 3))) + 1;

                    //in case this is a new year not past year
                    if ($year != $lastTwoDigitsOfYear) {
                        $lastTwoDigitsOfYear = $year;
                        $nextInvoiceNumber = 1;
                    }

                    if ($settings->is_new_year == 1) {
                        $nextInvoiceNumber = 1;
                        if ($settings->invoice_start) {
                            $nextInvoiceNumber = intval($settings->invoice_start);
                        }
                    }
                } else {
                    $nextInvoiceNumber = 1;

                    $lastTwoDigitsOfYear = $year;

                    if ($settings->is_new_year == 1) {
                        $lastTwoDigitsOfYear = $year;

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


                if ($totalAmount > 0) {
                    if (!is_null($rawExistBeforeForAgent)) {
                        $rawExistBeforeForAgent->update([
                            'total_amount' => $totalAmount,
                            'payment_received' => $allAmountFromDayOneUntilEndOfInvoice,
                            'old_balance' => $oldBalance,
                            'grand_total' => $totalAmount + $oldBalance,
                        ]);
                    } else {
                        $checkAgentIsHidden = Agent::query()->find($row['agent']['id']);

                        $lastValidInvoice = 1;

                        if ($checkAgentIsHidden->is_visible == 0) {

                            $invoiceTitle = $checkAgentIsHidden->name . ' ' . $this->from . ' - ' . $this->to;
                            $lastValidInvoice = 0;
                        }

                        // Create the AgentInvoice record
                        \App\Models\AgentInvoice::query()->create([
                            'agent_id' => $row['agent']['id'],
                            'invoice_title' => $invoiceTitle,
                            'from' => $this->from,
                            'to' => $this->to,
                            'last_valid_invoice' => $lastValidInvoice,
                            'total_amount' => $totalAmount,
                            'payment_received' => $allAmountFromDayOneUntilEndOfInvoice,
                            'old_balance' => $oldBalance,
                            'grand_total' => $totalAmount + $oldBalance
                        ]);
                    }
                }
            }
        }
        $this->showSaveInvoiceMessage = true;
    }

    public function hideSaveInvoiceMessage()
    {
        $this->showSaveInvoiceMessage = false;
    }

    public function endYear()
    {
        // Get the current date
        $currentDate = Carbon::now();

        // Determine the end of the previous or current year
        if ($currentDate->month == 1) {

            // If it's January, set from to the end of the previous year
            $this->from = $currentDate->subYear()->endOfYear()->copy()->previous(Carbon::FRIDAY)->format('Y-m-d');
        } else {
            // If it's any other month, set from to the end of the current year
            $this->from = $currentDate->endOfYear()->format('Y-m-d');
        }

        // Set to as the current date
        $this->to = $currentDate->format('Y-m-d');
        $this->saveInvoices();
    }

    public function startYear()
    {
        // Get the current year
        $currentYear = Carbon::now()->year;

        // Set "from" to January 1st of the current year
        $this->from = Carbon::createFromDate($currentYear, 1, 1)->format('Y-m-d');

        // Find the first Thursday in January of the current year
        $this->to = Carbon::createFromDate($currentYear, 1, 1)
            ->next(Carbon::THURSDAY)
            ->format('Y-m-d');

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
        $this->saveInvoices($agentId);
        $invoice = \App\Models\AgentInvoice::query()
            ->where('agent_id', $agentId)
            ->whereDate('from', $this->from)
            ->whereDate('to', $this->to)
            ->first();


        $url = route('admin.report.print.agent_invoices', ['agent' => $this->agentEmailed, 'fromDate' => $this->from, 'toDate' => $this->to, 'invoice' => $invoice->id]);
        $this->emit('printTable', $url);
    }

    public function getRecords($export = false, $agent = null, $from = null, $to = null)
    {

        $carbonFrom = Carbon::parse($this->from);
        $carbonFrom->subDay();
        if (!$this->agent && !$this->from && !$this->to) {
            return [];
        }
        if ($export) {
            $agentData = (new AgentInvoiceService())->getAgentData($agent, $from, $to);
            $agentData['agent'] = Agent::query()->find($this->agent); // Add agent_id to the data
            $data['agents'][] = $agentData;
            return $data;
        }
        return (new AgentInvoiceService())->getRecords($agent, $from, $to);
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

        $this->saveInvoices($this->agentEmailed);
        $invoice = \App\Models\AgentInvoice::query()
            ->where('agent_id', $this->agentEmailed)
            ->whereDate('from', $this->from)
            ->whereDate('to', $this->to)
            ->first();

        if (is_null($this->agentEmailed) || $this->agentEmailed == 'no_result') {
            $this->message = "You must choose travel agent";
            return;
        }

        $agent = Agent::query()->find($this->agentEmailed);

        $request->merge([
            'agent' => $this->agentEmailed,
            'fromDate' => $this->from,
            'toDate' => $this->to,
            'invoice' => $invoice->id,
        ]);


        $emails = explode(',', $this->email);
        foreach ($emails as $email) {
            Mail::to($email)->send(new AgentInvoiceMail($agent, $this->from, $this->to));
        }
        $this->toggleShowModal();

        //        $this->agent = null;
        //        return redirect()->to(route('admin.report.agent_invoices'));
    }


    public function exportReport(Request $request, $id)
    {
        $data = (new AgentInvoiceService())->getRecords($id, $this->from, $this->to);

        $this->saveInvoices($id);
        $invoice = \App\Models\AgentInvoice::query()
            ->where('agent_id', $id)
            ->whereDate('from', $this->from)
            ->whereDate('to', $this->to)
            ->first();

        $request->merge([
            'agent' => $id,
            'fromDate' => $this->from,
            'toDate' => $this->to,
            'invoice' => $invoice->id
        ]);
        $agent = Agent::query()->find($id);
        $name = $agent ?  $agent->name.'_invoice.csv' : 'agent_invoice.csv';

        $fileExport = (new \App\Exports\Reports\AgentInvoiceExport($data));
        return Excel::download($fileExport, $name);
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
        
        $records = $this->getRecords(false, $this->agent, $this->from, $this->to);
        return view('livewire.admin.reports.agent-invoice', compact('records'))->layout('layouts.admin');
    }
}
