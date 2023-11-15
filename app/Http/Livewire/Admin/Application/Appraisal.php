<?php

namespace App\Http\Livewire\Admin\Application;

use App\Models\Application;
use App\Models\VisaType;
use Livewire\Component;

class Appraisal extends Component
{
    public $visaTypes = [], $visaType;
    public function mount()
    {
        $this->page_title = __('admin.appraisal');
        $this->visaTypes = VisaType::query()->get();
    }

    public function getRecords()
    {
        return Application::query()
            ->when(!empty($this->visaType), function ($query) {
                return  $query->where('visa_type_id', $this->visaType);
            })
            ->latest()
            ->paginate();
    }

    public function showApplicationModal($id)
    {
        $this->emit("showApplicationModal", $id);
    }
    public function resetData()
    {
        $this->reset(['visa_type_id']);
    }

    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.application.appraisal', compact('records'))->layout('layouts.admin');
    }
}
