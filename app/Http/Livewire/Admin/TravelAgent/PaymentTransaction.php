<?php

namespace App\Http\Livewire\Admin\TravelAgent;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentTransaction extends Component
{
    use WithPagination;
    use ValidationTrait;

    public $name;

    public $is_active;
    public $perPage =10;
    public $search;
    public $agent,$rowNumber, $form, $agent_id, $message, $showPaymentHistory=false, $agentWhoWillAddToHimHistory;

    public $showAll= false;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showPaymentHistory','showAddPaymentHistory'];

    public function mount()
    {
        $this->page_title = __('admin.travel_agent_payment_transactions');
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function showAll()
    {
        $this->showAll = true;
        $this->agent = null;
        $this->emit('makeAgentNull');
    }
    public function getRecords()
    {
        if(\App\Helpers\isOwner()){
            $agents = Agent::query();
        }else{
            $agents= Agent::owner();
        }
            if($this->showAll || !is_null($this->agent)){
                return $agents->when($this->agent, function ($query) {
                    return $this->agent == 'no_result' ? $query->where('agents.id', '>', 0) : $query->where('agents.id', $this->agent);
                })
                    ->leftJoin('applications', 'agents.id', '=', 'applications.travel_agent_id')
                    ->leftJoin('payment_transactions', 'agents.id', '=', 'payment_transactions.agent_id')
                    ->leftJoin('service_transactions', function ($join) {
                        $join->on('agents.id', '=', 'service_transactions.agent_id')
                            ->where('service_transactions.status', '!=', 'deleted');
                    })
                    ->select(
                        'agents.*',
                        DB::raw('(SELECT COALESCE(SUM(dubai_fee + service_fee + vat), 0) FROM applications WHERE applications.travel_agent_id = agents.id) as amount'),
                        DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM payment_transactions WHERE payment_transactions.agent_id = agents.id) as amount_paid'),
                        DB::raw('(SELECT COALESCE(SUM(dubai_fee + service_fee + vat), 0) FROM service_transactions WHERE service_transactions.agent_id = agents.id AND service_transactions.status != "deleted") as amount_service')
                    )
                    ->groupBy('agents.id')
                    ->havingRaw('amount_paid > 0 OR amount_service > 0 OR amount > 0') // Filter based on your conditions
                    ->orderBy('agents.name')
                    ->latest()
                    ->paginate(50);

            }


        if(is_null($this->agent)){
            return [];
        }
    }

    public function showPaymentHistory($id)
    {
        $this->emit('showPaymentHistoryModal', $id);
    }

    public function showAddPaymentHistory($id)
    {
        $this->form['agent_id'] = $id;
        $this->emit('showAddPaymentHistoryModal', $id);
    }

    public function emptyForm()
    {
        $this->form =[];
    }

    public function resetData()
    {
        $this->reset(['agent_id']);
        return redirect()->to(route('admin.travel_agent_payment_transactions'));

    }

    public function store(Request $request)
    {
        $this->validate();
        if(isset($this->form['created_at'])){
            $this->form['updated_at'] = $this->form['created_at'];
        }

        \App\Models\PaymentTransaction::query()->create($this->form);

        $this->form = [];

        $this->emit('hideAddPaymentHistoryModal');
    }

    public function getRules(){
        return [
            'form.amount'=>'required',
            'form.created_at' => ['nullable']
        ];
    }

    public function render()
    {

        $records = $this->getRecords();
        return view('livewire.admin.travel-agent.payment-transaction',  compact('records'))->layout('layouts.admin');
    }
}
