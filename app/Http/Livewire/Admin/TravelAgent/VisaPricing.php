<?php

namespace App\Http\Livewire\Admin\TravelAgent;

use App\Models\Agent;
use Livewire\Component;
use App\Models\VisaType;
use Livewire\WithPagination;
use App\Models\AgentVisaPrice;

class VisaPricing extends Component
{
    use WithPagination;

    public $agentName;
    public $agentId;
    public $visaTypes = [];
    public $agentPrices = [];

    protected $rules = [
        'agentPrices.*.price' => 'required|numeric|min:0',
    ];

    public function mount($agentId)
    {
        $agent = Agent::query()->find($agentId);
        $this->agentName = $agent->name;
        $this->agentId = $agent->id;

        $this->loadVisaTypesAndPrices();
    }

    public function loadVisaTypesAndPrices()
    {
        // Load all visa types
        $this->visaTypes = VisaType::all();

        // Load agent-specific prices
        foreach ($this->visaTypes as $visaType) {
            $agentPrice = AgentVisaPrice::firstOrNew([
                'agent_id' => $this->agentId,
                'visa_type_id' => $visaType->id,
            ]);

            // Set the default value to Dubai Fee if no agent price exists
            $this->agentPrices[$visaType->id] = [
                'id' => $agentPrice->id,
                'price' => $agentPrice->price ?? $visaType->dubai_fee,
            ];
        }
    }

    public function savePrices()
    {
        $this->validate();


        foreach ($this->agentPrices as $visaTypeId => $priceData) {
            AgentVisaPrice::updateOrCreate(
                [
                    'agent_id' => $this->agentId,
                    'visa_type_id' => $visaTypeId,
                ],
                [
                    'price' => $priceData['price'],
                ]
            );
        }

        session()->flash('message', 'Prices saved successfully!');
    }

    public function render()
    {
        return view('livewire.admin.travel-agent.visa-pricing')->layout('layouts.admin');
    }
}
