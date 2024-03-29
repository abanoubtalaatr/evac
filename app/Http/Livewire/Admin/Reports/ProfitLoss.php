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
use Illuminate\Support\Facades\DB;
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
        if($this->from && $this->to){

            $fromDate = $this->from;
            $toDate = $this->to;

            $applicationFee = Application::whereBetween('created_at', [$fromDate, $toDate])
                ->sum(DB::raw('service_fee + dubai_fee + vat'));


            $serviceTransactionFee = ServiceTransaction::whereBetween('created_at', [$fromDate, $toDate])
                ->where('status', '!=', 'deleted')
                ->sum(DB::raw('service_fee + dubai_fee + vat'));


            $applicationServiceFees = Application::query()
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->sum('service_fee');

            $applicationDubaiFees = Application::query()
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->sum('dubai_fee');

            $applicationVats = Application::query()->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->sum('vat');

            $serviceTransactionServiceFees = ServiceTransaction::query()
                ->where('status', '!=', 'deleted')
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->sum('service_fee');

            $serviceTransactionDubaiFees = ServiceTransaction::query()
                ->where('status', '!=', 'deleted')
                ->whereDate('created_at', '>=',     $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->sum('dubai_fee');

            $serviceTransactionVats = ServiceTransaction::query()
                ->where('status', '!=', 'deleted')
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->sum('vat');


            $totalSales = $applicationFee + $serviceTransactionFee;


            $data['total_sales'] = $totalSales;
            $data['profit_loss'] = $totalSales - ($serviceTransactionDubaiFees + $applicationDubaiFees)- ($serviceTransactionVats+ $applicationVats);
            $data['vat'] = $applicationVats + $serviceTransactionVats;
            $data['dubai_fee'] = $applicationDubaiFees + $serviceTransactionDubaiFees;

            $paymentReceived = ServiceTransaction::whereBetween('created_at', [$fromDate, $toDate])
                ->where('status', '!=', 'deleted')
                ->whereNull('agent_id')
                ->where('payment_method', 'cash')
                ->sum(DB::raw('service_fee + dubai_fee + vat'));

            $paymentReceived += Application::whereBetween('created_at', [$fromDate, $toDate])
                ->whereNull('travel_agent_id')
                ->where('payment_method', 'cash')
                ->sum(DB::raw('service_fee + dubai_fee + vat'));


            $paymentReceived += PaymentTransaction::query()
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->sum('amount');
            $data['payments_received'] = $paymentReceived;
            return $data;
        }
        return [];
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
            Mail::to($email)->send(new ProfitLossMail( $this->from, $this->to));
        }

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
