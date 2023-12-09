<?php

namespace App\Http\Livewire\Admin\TravelAgent;

use App\Exports\Reports\AgentApplicationExport;
use App\Mail\AgentApplicationsMail;
use App\Models\Agent;
use App\Models\ServiceTransaction;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

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
        $message= null, $agent, $isDirect=false;

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

    public function setAgentToNull()
    {
        $this->agent = null;
        $this->isDirect = !$this->isDirect;
        $this->emit('agentSetToNull');
    }
    public function updatedAgent()
    {
        $this->isDirect = !$this->isDirect;
    }
    public function getRecords()
    {
        if ($this->isDirect) {
            $data['applications'] = \App\Models\Application::query()
                ->whereNull('travel_agent_id')
                ->when(!empty($this->from) && !empty($this->to), function ($query) {
                    $query->whereBetween('created_at', [$this->from, $this->to]);
                })
                ->latest()
                ->get();

            $data['serviceTransactions'] = ServiceTransaction::query()
                ->whereNull('agent_id')
                ->when(!empty($this->from) && !empty($this->to), function ($query) {
                    $query->whereBetween('created_at', [$this->from, $this->to]);
                })
                ->latest()
                ->get();
        } else {
            if ($this->agent === null) {
                return ['applications' => [], 'serviceTransactions' => []];
            }

            $data['applications'] = \App\Models\Application::query()
                ->when($this->agent !== 'no_result', function ($query) {
                    $query->where('travel_agent_id', $this->agent);
                })
                ->when($this->agent === 'no_result', function ($query) {
                    $query->where('travel_agent_id', '>', 0);
                })
                ->when(!empty($this->from) && !empty($this->to), function ($query) {
                    $query->whereBetween('created_at', [$this->from, $this->to]);
                })
                ->latest()
                ->get();

            $data['serviceTransactions'] = ServiceTransaction::query()
                ->when($this->agent !== 'no_result', function ($query) {
                    $query->where('agent_id', $this->agent);
                })
                ->when($this->agent === 'no_result', function ($query) {
                    $query->where('agent_id', '>', 0);
                })
                ->when(!empty($this->from) && !empty($this->to), function ($query) {
                    $query->whereBetween('created_at', [$this->from, $this->to]);
                })
                ->latest()
                ->get();
        }

        return $data;
    }

    public function sendEmail(Request $request)
    {
        $this->validate();
        if(is_null($this->agent) || $this->agent =='no_result') {
            $this->message = "You must choose travel agent";
            return;
        }
        $records = $this->getRecords()->groupBy('visa_type_id');
        $agent = Agent::query()->find($this->agent);
        $this->fromDate = $this->from;
        Mail::to($this->email)->send(new AgentApplicationsMail($records, $agent, $this->fromDate, $this->to));
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
