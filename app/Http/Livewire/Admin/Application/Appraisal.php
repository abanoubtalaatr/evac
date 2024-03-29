<?php

namespace App\Http\Livewire\Admin\Application;

use App\Http\Livewire\Traits\Admin\Application\CancelTrait;
use App\Http\Livewire\Traits\Admin\Application\DeleteTrait;
use App\Models\Agent;
use App\Models\Application;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Livewire\Component;

class Appraisal extends Component
{
    use CancelTrait;
    use DeleteTrait;

    public $visaTypes = [], $visaType;

    public function mount(Request $request)
    {
        $this->page_title = __('admin.appraisal');

        $this->visaTypes = VisaType::query()->get();
    }

    public function getRecords()
    {
        return Application::query()
            ->where('status', 'new')
            ->when(!empty($this->visaType), function ($query) {
                return  $query->where('visa_type_id', $this->visaType);
            })->orderBy('application_ref', 'desc')
            ->latest()
            ->paginate(50);
    }

    public function showApplicationModal($id)
    {
        $this->emit("showApplicationModal", $id);
    }
    public function resetData()
    {
        $this->reset(['visaType']);
    }

    public function appraiseAll()
    {
        if($this->getRecords()->count() > 0 ){
            foreach ($this->getRecords() as $application){
                $application->update(['status' => 'appraised']);
            }
        }
    }
    public function appraisal(Application $application)
    {
        $application->update(['status' => 'appraised']);
    }

    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.application.appraisal', compact('records'))->layout('layouts.admin');
    }
}
