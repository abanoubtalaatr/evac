<?php

namespace App\Http\Livewire\Admin\Application;

use App\Exports\ReceiptApplicationExport;
use App\Http\Livewire\Traits\Admin\Application\DeleteTrait;
use App\Mail\AgentApplicationsMail;
use App\Mail\ReceiptApplicationsMail;
use App\Models\Agent;
use App\Models\Application;
use App\Models\Setting;
use App\Models\VisaType;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;


class Revise extends Component
{
    use DeleteTrait;

    public $passport, $search, $visaTypes, $visaType, $status, $fullName, $referenceNo, $from, $to, $formInvoice,$application;
    protected $listeners = [ 'showApplicationInvoice'];
    public $showSendEmail, $email, $applicationThatSendByEmail;

    public function mount()
    {
        $this->page_title = __('admin.revise');
        $this->visaTypes = VisaType::query()->get();
    }

    public function showApplicationModal($id)
    {
        $this->emit("showApplicationModal", $id);
    }
    public function showApplicationInvoice($id)
    {
        $this->application = Application::query()->find($id);
        $this->formInvoice['payment_method'] = $this->application->payment_method;
        $this->formInvoice['amount'] = $this->application->amount;
        $this->formInvoice['vat'] = $this->application->vat;

        $this->emit("showApplicationInvoiceModal", $id);
    }

    public function updateInvoice()
    {
        $serviceFee = (new InvoiceService())->recalculateServiceFee($this->formInvoice['amount'], $this->application->dubai_fee);
        $vat = (new InvoiceService())->recalculateVat($this->formInvoice['amount'], $this->application->dubai_fee);

        $this->application->update([
            'payment_method' => $this->formInvoice['payment_method'],
            'amount' => $this->formInvoice['amount'],
            'vat' => $vat,
            'service_fee' => $serviceFee,
        ]);

        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.applications.revise'));

    }

    public function downloadCSV($id)
    {
        $application = \App\Models\Application::query()->find($id);
        return Excel::download(new ReceiptApplicationExport($application), 'applicationReceipt.csv');
    }

    public function send()
    {
        if(!empty($this->email)){
            Mail::to($this->email)->send(new ReceiptApplicationsMail($this->applicationThatSendByEmail));
            $this->email = null;
            $this->showSendEmail = !$this->showSendEmail;
        }
    }

    public function toggleShowModal($id=null)
    {
        if($id){
            $this->applicationThatSendByEmail = Application::find($id);

        }
        $this->showSendEmail = !$this->showSendEmail;
    }
    public function getRecords()
    {
        return Application::query()
            ->when(!empty($this->passport), function ($query) {
                $query->where(function ($query) {
                    $query->where('passport_no', 'like', '%'.$this->passport.'%');
                });
            })->when(!empty($this->fullName), function ($query) {
                $query->where(function ($query) {
                    $query->where('first_name', 'like', '%'.$this->fullName.'%')
                        ->orWhere('last_name', 'like', '%'. $this->fullName . '%');
                });
            })->when(!empty($this->referenceNo), function ($query) {
                $query->where(function ($query) {
                    $query->where('application_ref', 'like', '%'.$this->referenceNo.'%');
                });
            })->when(!empty($this->visaType), function ($query){
                $query->where('visa_type_id', $this->visaType);
            })->when(!empty($this->status), function ($query) {
                $query->where(function ($query) {
                    $query->where('status', 'like', '%'.$this->status.'%');
                });
            })->when(!empty($this->from) && !empty($this->to), function ($query) {
                $query->whereBetween('created_at', [$this->from, $this->to]);
            })
            ->latest()
            ->paginate(50);
    }

    public function resetData()
    {
        $this->reset(['visaType', 'passport', 'status', 'fullName', 'from', 'to']);
    }

    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.application.revise', compact('records'))->layout('layouts.admin');
    }
}
