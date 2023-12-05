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

    public function getRecords()
    {
        return Agent::query()->when($this->agent, function ($query) {
            if($this->agent =='no_result'){
                return $query->where('agents.id', '>', 0);

            }else{
                return $query->where('agents.id', $this->agent);
            }
        })
            ->leftJoin('applications', 'agents.id', '=', 'applications.travel_agent_id')
            ->leftJoin('payment_transactions', function ($join) {
                $join->on('agents.id', '=', 'payment_transactions.agent_id');
            })
            ->leftJoin('service_transactions', function ($join) {
                $join->on('agents.id', '=', 'service_transactions.agent_id');
            })
            ->select(
                'agents.*',
                DB::raw('(SELECT SUM(amount) FROM applications WHERE applications.travel_agent_id = agents.id) as amount'),
                DB::raw('(SELECT SUM(COALESCE(amount, 0)) FROM payment_transactions WHERE payment_transactions.agent_id = agents.id) as amount_paid'),
                DB::raw('(SELECT SUM(COALESCE(amount, 0)) FROM service_transactions WHERE service_transactions.agent_id = agents.id) as amount_service')
            )
            ->groupBy('agents.id')
            ->latest()
            ->paginate(50);

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
    }

    public function store(Request $request)
    {
        $this->validate();

        \App\Models\PaymentTransaction::query()->create($this->form);

        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.travel_agent_payment_transactions'));
    }

    public function getRules(){
        return [
            'form.amount'=>'required',
        ];
    }

    public function render()
    {

        $records = $this->getRecords();
        return view('livewire.admin.travel-agent.payment-transaction',  compact('records'))->layout('layouts.admin');
    }
}
