<?php

namespace App\Http\Livewire\Admin\Application;

use App\Models\Application;
use App\Models\VisaType;
use Livewire\Component;

class Revise extends Component
{
    public $passport, $search, $visaTypes, $visaType, $status, $fullName, $referenceNo, $from, $to;

    public function mount()
    {
        $this->page_title = __('admin.revise');
        $this->visaTypes = VisaType::query()->get();
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
