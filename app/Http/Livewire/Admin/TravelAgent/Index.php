<?php

namespace App\Http\Livewire\Admin\TravelAgent;

use App\Exports\ReceiptApplicationExport;
use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Agent;
use App\Services\Application\CSVService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function mount()
    {
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

    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.travel-agent.index',  compact('records'))->layout('layouts.admin');
    }
}
