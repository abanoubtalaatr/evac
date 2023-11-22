<?php

namespace App\Http\Livewire\Admin\TravelAgent;

use App\Models\VisaType;
use Livewire\Component;

class Application extends Component
{
    public $page_title, $visaTypes, $from, $to, $visaType, $status, $agent_id, $email;

    public function mount()
    {
        $this->page_title = __('agent_applications');
        $this->visaTypes = VisaType::query()->get();
    }

    public function getRecords()
    {
        return \App\Models\Application::query()
            ->when(!empty($this->passport), function ($query) {
                $query->where(function ($query) {
                    $query->where('passport_no', 'like', '%'.$this->passport.'%');
                });
            })->when(!empty($this->agent_id), function ($query) {
                $query->where('travel_agent_id', $this->agent_id);
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

    public function send()
    {
        $this->validate();
        dd('send your now for this now ');
    }
    public function getRules()
    {
        return [
          'email' => ['required', 'email']
        ];
    }

    public function resetData()
    {
        $this->reset(['from', 'to', 'agent_id', 'visaType', 'status']);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.travel-agent.application', compact('records'))->layout('layouts.admin');;
    }
}
