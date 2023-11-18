<?php

namespace App\Http\Livewire\Admin\Application;

use App\Http\Livewire\Traits\Admin\Application\DeleteTrait;
use App\Models\Application;
use App\Models\Setting;
use App\Models\VisaType;
use Livewire\Component;
use function App\Helpers\vatRate;

class Revise extends Component
{
    use DeleteTrait;

    public $passport, $search, $visaTypes, $visaType, $status, $fullName, $referenceNo, $from, $to, $formInvoice,$application;
    protected $listeners = [ 'showApplicationInvoice'];

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
        $vat = $this->formInvoice['vat'];
        $vatRAte = vatRate($this->application->visaType->id);

        if($this->application->amount != $this->formInvoice['amount']) {
            if(($this->formInvoice['amount'] - $this->application->visaType->dubai_fee) > 0) {
                $vat = $this->formInvoice['amount'] -  $this->application->visaType->dubai_fee * $vatRAte;
            }else{
                $vat = 0;
            }
        }

        $this->application->update([
            'payment_method' => $this->formInvoice['payment_method'],
            'amount' => $this->formInvoice['amount'],
            'vat' => $vat,
        ]);

        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.applications.revise'));

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
            ->paginate();
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
