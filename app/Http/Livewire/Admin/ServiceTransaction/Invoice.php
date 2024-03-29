<?php

namespace App\Http\Livewire\Admin\ServiceTransaction;

use App\Http\Livewire\Traits\Admin\ServiceTransaction\PayInvoiceTrait;
use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\ServiceTransaction;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Invoice extends Component
{
    use WithPagination;
    use ValidationTrait;
    use PayInvoiceTrait;

    public $name, $surname, $agent,$service,$from,$to, $passport;
    public $form;
    public $is_active;
    public $perPage =10;
    public $services, $agents, $passportNo;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showServiceTransaction', 'showServiceTransactionInvoice'];

    public function mount()
    {
        $this->page_title = __('admin.service_transaction_invoices');
        $this->form['createDate'] = Carbon::now()->format('Y-m-d');

    }

    public function resetData()
    {
        return redirect()->to(route('admin.service_transactions.invoices'));
    }

    public function searchInvoices()
    {

        $this->getRecords();
    }
    public function getRecords()
    {
        return ServiceTransaction::query()
            ->where('agent_id', null)
            ->where('payment_method', 'invoice')
            ->when(!empty($this->name), function ($query) {
                $query->where('name', 'like', '%'.$this->name.'%');
            })->when(!empty($this->passportNo), function ($query) {
                $query->where('name', 'like', '%'.$this->passportNo.'%');
            })->when(!empty($this->surname), function ($query){
                $query->where('surname', 'like', '%'.$this->surname.'%');
            })->when(!empty($this->passport), function ($query){
                $query->where('passport_no', 'like', '%'.$this->passport.'%');
            })->when(!empty($this->agent), function ($query){
                $query->whereHas('agent', function ($query){
                    if($this->agent != 'no_result'){
                        $query->where('id',$this->agent);
                    }else{
                        $query->where('id', '>',0);
                    }
                });
            })->when(!empty($this->service), function ($query){
                $query->whereHas('service', function ($query){
                    $query->where('name', 'like', '%'.$this->service.'%');
                });
            })->when(!empty($this->from) && !empty($this->to), function ($query) {
                $query->whereBetween('created_at', [$this->from, $this->to]);
            })

            ->latest()
            ->paginate(50);
    }

    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.service-transaction.invoices', compact('records'))->layout('layouts.admin');
    }
}
