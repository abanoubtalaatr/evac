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
        $message= null, $agent = null, $isDirect=false;


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
    public function printData()
    {
        $url = route('admin.travel_agents_print_applications', ['agent' => $this->agent,'isDirect' => $this->isDirect,'fromDate' => $this->from,'toDate' => $this->to]);
        $this->emit('printTable', $url);
    }


    public function updatedAgent()
    {
        $this->isDirect = false;
    }
    public function getRecords()
    {
        if ($this->isDirect) {
            $data['applications'] = \App\Models\Application::query()
                ->whereNull('travel_agent_id')
                ->when(!empty($this->from) && !empty($this->to), function ($query) {
                    $query->whereDate('created_at', '>=', $this->from)
                        ->whereDate('created_at', '<=', $this->to);
                })
                ->latest()
                ->get();

            $data['serviceTransactions'] = ServiceTransaction::query()
                ->whereNull('agent_id')
                ->when(!empty($this->from) && !empty($this->to), function ($query) {
                    $query->whereDate('created_at', '>=', $this->from)
                        ->whereDate('created_at', '<=', $this->to);
                })
                ->latest()
                ->get();
        } else {

            if ($this->agent === null || empty($this->agent)) {
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
                    $query->whereDate('created_at', '>=', $this->from)
                        ->whereDate('created_at', '<=', $this->to);
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
                    $query->whereDate('created_at', '>=', $this->from)
                        ->whereDate('created_at', '<=', $this->to);
                })
                ->latest()
                ->get();
        }

        return $data;
    }

    public function sendEmail(Request $request)
    {
        
        $this->validate();
        if(!$this->isDirect && is_null($this->agent) || $this->agent =='no_result') {
            $this->message = "You must choose travel agent";
            return;
        }
        if($this->agent){
            $agent = Agent::query()->find($this->agent);
        }else{
            $agent = null;
        }

        $request->merge([
           'agent' => $this->agent,
           'fromDate' => $this->from,
           'toDate' => $this->to,
           'isDirect' => $this->isDirect,
        ]);

        $emails = explode(',', $this->email);
        foreach ($emails as $email){
            Mail::to($email)->send(new AgentApplicationsMail($this->getRecords(),$agent, $this->from, $this->to));
        }
        $this->email = null;
        $this->message = null;
        $this->toggleShowModal();
//        $this->agent = null;
//        return redirect()->to(route('admin.travel_agent_applications'));
    }


    public function exportReport()
    {
        $fileExport = (new \App\Exports\Reports\AgentApplicationExport($this->getRecords(), $this->agent, $this->from, $this->to));
        $rowAgent = Agent::query()->find($this->agent);
        $name ='report.csv';
        if($rowAgent){
            $name = $rowAgent->name .'.csv';
        }
        return Excel::download($fileExport, $name);
    }

    public function getRules()
    {
        return [
          'email' => ['required']
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
