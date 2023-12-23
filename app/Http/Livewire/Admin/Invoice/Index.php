<?php

namespace App\Http\Livewire\Admin\Invoice;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Agent;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use function App\Helpers\isOwner;

class Index extends Component
{
    use WithPagination;
    use ValidationTrait;

    public $name;
    public $form;
    public $is_active;
    public $perPage =10;
    public $search;
    public $invoice;
    public $agent;
    public $agents;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showInvoice'];

    public function mount()
    {
        $this->page_title = __('admin.management_invoice');
        if(isOwner()){
            $this->agents = Agent::query()->get();

        }else{
            $this->agents = Agent::owner()->get();

        }
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function showInvoice($id)
    {
        $this->invoice = \App\Models\AgentInvoice::query()->find($id);
        $this->form = $this->invoice->toArray();
        $this->form['created_at'] = Carbon::parse($this->invoice->created_at)->format('Y-m-d');
        $this->emit("showInvoiceModal", $id);
    }

    public function emptyForm()
    {
        $this->form =[];
        $this->resetValidation();
    }

    public function getRecords()
    {
        if(isOwner()) {
            if($this->agent && $this->agent !='no_result' ){
                return \App\Models\AgentInvoice::query()
                    ->orderBy('agent_id')
                    ->where('agent_id', $this->agent)
                    ->latest()
                    ->paginate(50);
            }
            return \App\Models\AgentInvoice::query()
                ->orderBy('agent_id')
                ->where('agent_id', $this->agent)
                ->latest()
                ->paginate(50);
        }else{
            if($this->agent && $this->agent !='no_result' ){
                return \App\Models\AgentInvoice::query()
                    ->orderBy('agent_id')
                    ->whereHas('agent', function ($agent){
                        $agent->where('is_visible', 1);
                    })->where('agent_id', $this->agent)
                    ->latest()
                    ->paginate(50);
            }
            return \App\Models\AgentInvoice::query()
                ->orderBy('agent_id')
                ->whereHas('agent', function ($agent){
                    $agent->where('is_visible', 1);
                })->latest()
                ->paginate(50);
        }

    }

    public function update()
    {
        $this->validate();
        $data = Arr::except($this->form,['id', 'updated_at' ]);

        \App\Models\AgentInvoice::query()->find($this->form['id'])->update($data);

        $this->form = [];
        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.invoices'));
    }

    public function store()
    {
        $this->validate();
        $invoice = \App\Models\AgentInvoice::query()->create($this->form);

        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.invoices'));
    }

    public function destroy(\App\Models\AgentInvoice $invoice)
    {
        $invoice->delete();
    }

    public function resetData()
    {
        return redirect()->to(route('admin.invoices'));

    }

    public function getRules(){
        return [
            'form.invoice_title'=>'required',
            'form.created_at'=> 'required|date',
            'form.total_amount'=>'required',
        ];
    }
    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.invoice.index', compact('records'))->layout('layouts.admin');
    }
}
