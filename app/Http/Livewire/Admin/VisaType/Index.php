<?php

namespace App\Http\Livewire\Admin\VisaType;

use App\Models\Agent;
use Livewire\Component;
use App\Models\VisaType;
use Illuminate\Support\Arr;
use App\Models\VisaProvider;
use Livewire\WithPagination;
use App\Models\AgentVisaPrice;
use App\Http\Livewire\Traits\ValidationTrait;

class Index extends Component
{
    use WithPagination;
    use ValidationTrait;

    public $name;
    public $form;
    public $is_active;
    public $perPage =10;
    public $search;
    public $visaType;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showVisaType'];

    public function mount()
    {
        $this->page_title = __('admin.visa_types');
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function showVisaType($id)
    {
        $this->visaType = VisaType::query()->find($id);
        $this->form = $this->visaType->toArray();

        $this->emit("showVisaTypeModal", $id);
    }

    public function makeDefault(VisaType $visaType)
    {
        VisaType::where('is_default', 1)->update(['is_default' => 0]);

        $visaType->update(['is_default' => 1]);
    }
    public function getRecords()
    {
        return VisaType::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('dubai_fee', 'like', '%'.$this->search.'%')
                        ->orWhere('service_fee', 'like', '%'.$this->search.'%');
                });
            })
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
        return redirect()->to(route('admin.visa_types'));

    }
    public function update()
    {
        $this->validate();
        $data = Arr::except($this->form,['id', 'updated_at', 'created_at']);
        $data['total'] = intval($this->form['dubai_fee']) + intval($this->form['service_fee']);
        VisaType::query()->find($this->form['id'])->update($data);

        $this->form = [];
        $visas = VisaType::all();

        foreach($visas as $visa)
        {
            $agents = Agent::all();

            foreach($agents as $agent)
            {
                $agentVisaPrice = AgentVisaPrice::where('agent_id', $agent->id)->where('visa_type_id')->first();
                if($agentVisaPrice){
                    continue;
                }
                AgentVisaPrice::create([
                    'visa_type_id' => $visa->id,
                    'agent_id' => $agent->id,
                    'price' => $visa->service_fee
                ]);
            }
               
        }
        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.visa_types'));
    }

    public function store()
    {
        $this->validate();
        $this->form['total'] = intval($this->form['dubai_fee']) + intval($this->form['service_fee']);
        $visaType = VisaType::query()->create($this->form);

        if(VisaType::query()->count() == 1){
            $visaType->update(['is_default' => 1]);
        }

        $visas = VisaType::all();

        foreach($visas as $visa)
        {
            $agents = Agent::all();

            foreach($agents as $agent)
            {
                $agentVisaPrice = AgentVisaPrice::where('agent_id', $agent->id)->where('visa_type_id')->first();
                if($agentVisaPrice){
                    continue;
                }
                AgentVisaPrice::create([
                    'visa_type_id' => $visa->id,
                    'agent_id' => $agent->id,
                    'price' => $visa->service_fee
                ]);
            }
               
        }

        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.visa_types'));
    }

    public function getRules(){
        return [
            'form.name'=>'required|max:500',
            'form.service_fee'=>'required|max:500',
            'form.dubai_fee'=>'required|max:500',
            'form.total'=>'nullable',
        ];
    }
    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.visa-type.index', compact('records'))->layout('layouts.admin');
    }
}
