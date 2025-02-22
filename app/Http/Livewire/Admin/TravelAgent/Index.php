<?php

namespace App\Http\Livewire\Admin\TravelAgent;

use App\Models\Agent;
use Livewire\Component;
use App\Models\VisaType;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Models\AgentVisaPrice;
use App\Services\Application\CSVService;
use App\Exports\ReceiptApplicationExport;
use App\Http\Livewire\Traits\ValidationTrait;

class Index extends Component
{
    use WithPagination;
    use ValidationTrait;

    public $name;

    public $is_active = '1';
    public $perPage =10;
    public $search;
    public $agent,$rowNumber;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showAgent'];
    public $serviceFee =null;
    public $visaTypes = [];
    public $visa_type_id = null;

    public function mount()
    {
        $this->visaTypes = VisaType::all();

        $this->page_title = __('admin.agents');
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {

        if(\App\Helpers\isOwner()){
            $agents = Agent::query();
        }else{
            $agents= Agent::owner();
        }

        return $agents
            ->when(!empty($this->is_active), function ($query) {
                if($this->is_active == "1") {
                    return  $query->where('is_active', "1");
                }

                if($this->is_active == 'not_active') {
                    return  $query->where('is_active', "0");
                }

            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('contact_name', 'like', '%'.$this->search.'%')

                        ->orWhere('name', 'like', '%'.$this->search.'%')
                        ->orWhere('telephone', 'like', '%'.$this->search.'%')
                        ;
                });
            })->when($this->agent, function ($query){
                if($this->agent == 'no_result'){
                    $query->where('id', '>', 0);
                }else{
                    $query->where('id', $this->agent);
                }
            })
            ->orderBy('name')
            ->latest()
            ->paginate(50);

    }

    public function emptyForm()
    {
        $this->form =[];
        $this->resetValidation();
    }
    public function resetData()
    {
        return redirect()->to(route('admin.travel_agents'));
//        $this->reset(['is_active', 'search', 'ag']);
    }


    public function store(Request $request)
    {
        $this->validate();

        $travelAgent = Agent::query()->create($this->form);
        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.travel_agents'));
    }

    public function showAgent($id)
    {
        $this->form = [];
        $this->agentId = $id;
        $this->agentData = Agent::query()->find($id);

        $this->form = $this->agentData->toArray();
        $this->emit("showAgentModal", $id);
    }

    public function update()
    {
        $this->validate();
        $data = Arr::except($this->form,['id', 'updated_at', 'created_at', 'is_active']);

        $agent = Agent::query()->find($this->form['id'])->update($data);

        $this->form = [];
        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.travel_agents'));
    }

    public function toggleStatus(Agent $agent)
    {
        $agent->update(['is_active' => !$agent->is_active]);
    }


    public function getRules(){
        return [
            'form.name'=>'required|max:500',
            'form.company_name'=>'nullable|max:500',
            'form.address'=>'required|max:500',
            'form.mobile'=>'required|max:500',
            'form.email'=>'required|max:500',
            'form.contact_name'=>'required|max:500',
            'form.owner_name'=>'required|max:500',
            'form.iata_no'=>'nullable|max:500',
            'form.finance_no'=>'required|max:500',
            'form.telephone'=>'required|max:500',
            'form.account_number' => ['required'],
        ];
    }
    public function saveAgentPrices()
{
    if ($this->serviceFee && $this->visa_type_id) {
        $agents = Agent::all();  // Retrieve agents once instead of inside the loop
    
        foreach ($agents as $agent) {
            // Use updateOrCreate to update or create the agent price for the selected visa type
            AgentVisaPrice::updateOrCreate(
                [
                    'agent_id' => $agent->id,
                    'visa_type_id' => $this->visa_type_id
                ],
                [
                    'price' => $this->serviceFee
                ]
            );
        }

        session()->flash('success', __('admin.edit_successfully'));

        // Reload the page after saving
        return redirect()->to(route('admin.travel_agents'));
    } else {
        session()->flash('error', __('Please select a valid visa type and enter a valid price.'));
    }
}

public function render()
{
    $records = $this->getRecords(); // Get your agents or other records
    return view('livewire.admin.travel-agent.index', [
        'visaTypes' => $this->visaTypes,  // Pass visaTypes to the view
        'records' => $records             // Pass records (agents or other data) to the view
    ])->layout('layouts.admin');
}


    
}
