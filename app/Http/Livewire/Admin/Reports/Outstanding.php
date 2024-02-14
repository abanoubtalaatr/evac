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

                $totalSalesForAllAgent += $totalSales;
                $totalUnPaidForEveryAgent = $totalSales - $paidBal;

                $totalUnPaidBal += $totalUnPaidForEveryAgent;

                $totalSalesByAgent[] = [
                    'agent_id' => $agent->id,
                    'agent_name' => $agent->name,
                    'total_sales' => $totalSales,
                    'un_paid_bail' => $totalSales - $paidBal,
                ];

            }

            $applications = Application::query()->whereNull('travel_agent_id')->groupBy('first_name', 'last_name')->get();
            $totalDirectSales = 0;
            $totalUnPaidBalDirect =0;
            $unpaid = 0;
            $totalAmount = 0;

        foreach ($applications as $row) {
            $applicationsForPersons = Application::query()
                ->where('first_name', $row->first_name)
                ->where('last_name', $row->last_name)
                ->get();

            foreach ($applicationsForPersons as $applicationsForPerson) {
                $totalAmount = $applicationsForPerson->dubai_fee + $applicationsForPerson->service_fee + $applicationsForPerson->vat;
                $totalDirectSales += $totalAmount;
                $unpaid = 0;

                if ($applicationsForPerson->payment_method == 'invoice') {
                    $unpaid = $totalAmount;
                    $totalUnPaidBalDirect += $totalAmount;
                }

                $key = $row->first_name . $row->last_name;

                if (isset($data['directs'][$key])) {
                    // Key exists, add total amount to existing total
                    $data['directs'][$key]['total'] += $totalAmount;
                    $data['directs'][$key]['un_paid'] += $unpaid;
                } else {
                    // Key doesn't exist, create a new entry
                    $data['directs'][$key] = [
                        'name' => $row->first_name . ' ' . $row->last_name,
                        'total' => $totalAmount,
                        'un_paid' => $unpaid,
                    ];
                }
            }
        }

        $serviceTransactions = ServiceTransaction::query()->where('status', '!=', 'deleted')->groupBy('name', 'surname')->whereNull('agent_id')->get();

        foreach ($serviceTransactions as $row) {
            $serviceTransactionsForPersons = ServiceTransaction::query()->where('status', '!=', 'deleted')
                ->where('name', $row->name)
                ->where('surname', $row->surname)
                ->get();

            foreach ($serviceTransactionsForPersons as $serviceTransactionsForPerson) {
                $totalAmount = $serviceTransactionsForPerson->dubai_fee + $serviceTransactionsForPerson->service_fee + $serviceTransactionsForPerson->vat;
                $totalDirectSales += $totalAmount;
                $unpaid = 0;

                if ($serviceTransactionsForPerson->payment_method == 'invoice') {
                    $unpaid = $totalAmount;
                    $totalUnPaidBalDirect += $totalAmount;
                }

                $key = $row->name . $row->surname;

                if (isset($data['directs'][$key])) {
                    // Key exists, add total amount to existing total
                    $data['directs'][$key]['total'] += $totalAmount;
                    $data['directs'][$key]['un_paid'] += $unpaid;
                } else {
                    // Key doesn't exist, create a new entry
                    $data['directs'][$key] = [
                        'name' => $row->name . ' ' . $row->surname,
                        'total' => $totalAmount,
                        'un_paid' => $unpaid,
                    ];
                }
            }
        }
        $data['agents'] = $totalSalesByAgent;
        $data['total_sales_for_all_agents'] = $totalSalesForAllAgent;
        $data['total_un_paid_bal_for_agents'] = $totalUnPaidBal;
        $data['total_sales_for_direct'] = $totalDirectSales;
        $data['total_un_paid_bal_for_direct'] = $totalUnPaidBalDirect;
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
