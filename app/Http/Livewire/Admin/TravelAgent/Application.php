<?php

namespace App\Http\Livewire\Admin\TravelAgent;

use App\Mail\AgentApplicationsMail;
use App\Models\Agent;
use App\Models\VisaType;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Application extends Component
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
        $showSendEmailButton= false,
        $message= null, $agent;

    public function mount()
    {
        $this->page_title = __('admin.agent_applications');
        $this->visaTypes = VisaType::query()->get();
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

    public function getRecords()
    {
        return \App\Models\Application::query()
            ->when(!empty($this->passport), function ($query) {
                $query->where(function ($query) {
                    $query->where('passport_no', 'like', '%'.$this->passport.'%');
                });
            })->when($this->agent, function ($query){
                if($this->agent == 'no_result'){
                    $query->where('travel_agent_id', '>', 0);
                }else{
                    $query->where('travel_agent_id', $this->agent);
                }
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

        if(is_null($this->agent) || $this->agent =='no_result') {
            $this->message = "You must choose travel agent";
            return;
        }
        $records = $this->getRecords()->groupBy('visa_type_id');
        $agent = Agent::query()->find($this->agent);
        Mail::to($this->email)->send(new AgentApplicationsMail($records, $agent, $this->from, $this->to));
        $this->email = null;
        $this->message = null;
        $this->agent = null;
        return redirect()->to(route('admin.travel_agent_applications'));
    }
    public function getRules()
    {
        return [
          'email' => ['required', 'email']
        ];
    }

    public function resetData()
    {
        return redirect()->to(route('admin.travel_agent_applications'));
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.travel-agent.application', compact('records'))->layout('layouts.admin');;
    }
}
