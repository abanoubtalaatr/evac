<?php

namespace App\Http\Livewire\Admin\Application;

use App\Http\Livewire\Traits\Admin\Application\PayInvoiceTrait;
use App\Models\Application;
use App\Models\VisaType;
use Livewire\Component;

class UnPaidApplication extends Component
{
    use PayInvoiceTrait;

    public $passport, $search, $visaTypes, $visaType, $status, $firstName,$lastName, $paymentMethod, $from, $to,$application;
    protected $listeners = [ 'showApplicationInvoice'];

    public function mount()
    {
        $this->page_title = __('admin.payment_applications');
        $this->visaTypes = VisaType::query()->get();
    }

    public function getRecords()
    {
        return Application::query()
            ->where('travel_agent_id', null)
            ->where('payment_method', 'invoice')
            ->when(!empty($this->passport), function ($query) {
                $query->where(function ($query) {
                    $query->where('passport_no', 'like', '%'.$this->passport.'%');
                });
            })->when(!empty($this->firstName), function ($query) {
                $query->where(function ($query) {
                    $query->where('first_name', 'like', '%'.$this->firstName.'%');
                });
            })->when(!empty($this->lastName), function ($query) {
                $query->where(function ($query) {
                    $query->where('last_name', 'like', '%'.$this->lastName.'%');
                });
            })->when(!empty($this->paymentMethod), function ($query) {
                $query->where(function ($query) {
                    $query->where('payment_method', 'like', '%'.$this->paymentMethod.'%');
                });
            })->when(!empty($this->from) && !empty($this->to), function ($query) {
                $query->whereBetween('created_at', [$this->from, $this->to]);
            })
            ->latest()
            ->paginate(50);
    }


    public function resetData()
    {
        $this->reset(['visaType', 'passport', 'status', 'firstName','lastName', 'from', 'to']);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.application.un-paid-application', compact('records'))->layout('layouts.admin');
    }
}
