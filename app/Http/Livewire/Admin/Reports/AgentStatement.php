<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Mail\Reports\AgentStatementMail;
use App\Models\Agent;
use App\Models\PaymentTransaction;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use function App\Helpers\isOwner;

class AgentStatement extends Component
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
        $message = null, $agent, $payment_method, $disableSendForAdminsButton = false;

    public function mount()
    {
        $this->page_title = __('admin.agent_statement');
        $this->visaTypes = VisaType::query()->get();
    }

    public function updatedAgentId()
    {
        if (!is_null($this->agent)) {
            $this->showSendEmailButton = true;
        } else {
            $this->showSendEmailButton = false;
        }
    }

    public function toggleShowModal()
    {
        $this->email = null;
        $this->showSendEmail = !$this->showSendEmail;
    }

    public function setAgentToNull()
    {
        $this->agent = null;
        $this->emit('agentSetToNull');
    }
    public function printData()
    {
        $url = route('admin.report.print.agent_statement', ['agent' => $this->agent, 'fromDate' => $this->from, 'toDate' => $this->to]);
        $this->emit('printTable', $url);
    }

    public function getRecords()
    {
        $data = [];
        if (isOwner()) {
            if (isset($this->agent)) {
                $invoiceQuery = \App\Models\AgentInvoice::query()->where('agent_id', $this->agent);
                $paymentQuery = PaymentTransaction::query()->where('agent_id', $this->agent);

                // Add date range filters if provided
                if ($this->from && $this->to) {
                    $invoiceQuery->whereDate('from', '>=', $this->from)
                        ->whereDate('to', '<=', $this->to);
                    $paymentQuery->whereDate('created_at', '>=', $this->from)
                        ->whereDate('created_at', '<=', $this->to);
                }

                $data['invoices'] = $invoiceQuery->get();
                $data['payment_received'] = $paymentQuery->get();
            }
        } else {
            if (isset($this->agent)) {
                $invoiceQuery = \App\Models\AgentInvoice::query()
                    ->whereHas('agent', function ($agent) {
                        $agent->where('is_visible', 1);
                    })
                    ->where('agent_id', $this->agent);
                $paymentQuery = PaymentTransaction::query()
                    ->whereHas('agent', function ($agent) {
                        $agent->where('is_visible', 1);
                    })
                    ->where('agent_id', $this->agent);

                // Add date range filters if provided
                if ($this->from && $this->to) {
                    $invoiceQuery->whereDate('from', '>=', $this->from)
                        ->whereDate('to', '<=', $this->to);
                    $paymentQuery->whereDate('created_at', '>=', $this->from)
                        ->whereDate('created_at', '<=', $this->to);
                }

                $data['invoices'] = $invoiceQuery->get();
                $data['payment_received'] = $paymentQuery->get();
            }
        }

        if ($data) {

            // Combine and order the results
            $combinedResults = collect($data['invoices'])
                ->merge($data['payment_received'])
                ->sortBy(function ($item) {
                    // Assuming 'created_at' for invoices and 'created_at' for payment_received
                    return $item instanceof \App\Models\AgentInvoice ? $item->from : $item->created_at;
                })
                ->values()
                ->all();

            // Calculate the total sum of total_amount from invoices
            $totalDrCount = collect($data['invoices'])->sum('total_amount');

            // Calculate the total sum of amount from payment_received
            $totalCrCount = collect($data['payment_received'])->sum('amount');

            $data['combined_results'] = $combinedResults;
            $data['totalDrCount'] = $totalDrCount;
            $data['totalCrCount'] = $totalCrCount;


            // Calculate the total sum of total_amount from invoices
            $totalDrCount = collect($data['invoices'])->sum('total_amount');

            // Calculate the total sum of amount from payment_received
            $totalCrCount = collect($data['payment_received'])->sum('amount');

            $data['totalDrCount'] = $totalDrCount;
            // dd($data);
            $data['totalCrCount'] = $totalCrCount;

            $data['data'] = $combinedResults;
        }

        return $data;
    }


    public function sendEmail(Request $request)
    {
        $this->validate();

        if (is_null($this->agent) || $this->agent == 'no_result') {
            $this->message = "You must choose travel agent";
            return;
        }

        $agent = Agent::query()->find($this->agent);

        $request->merge([
            'agent' => $this->agent,
            'fromDate' => $this->from,
            'toDate' => $this->to,
        ]);

        $emails = explode(',', $this->email);
        foreach ($emails as $email) {
            Mail::to($email)->send(new AgentStatementMail($agent->id, $this->from, $this->to));
        }

        $this->email = null;
        $this->message = null;
        //        $this->agent = null;
        $this->toggleShowModal();
        //        return redirect()->to(route('admin.report.agent_statement'));
    }


    public function exportReport()
    {
        $fileExport = (new \App\Exports\Reports\AgentStatementExport($this->getRecords(), $this->agent));
        $agent = Agent::query()->find($this->agent);
        $name = $agent ?  $agent->name . '_statement.csv' : 'agent_statement.csv';
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
        $records = $this->getRecords();

        return view('livewire.admin.reports.agent-statement', compact('records'))->layout('layouts.admin');
    }
}
