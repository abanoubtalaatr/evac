<?php

namespace App\Http\Livewire\Admin\ServiceTransaction;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Agent;
use App\Models\Application;
use App\Models\Service;
use App\Models\ServiceTransaction;
use App\Models\Setting;
use App\Models\VisaType;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use ValidationTrait;

    public $name;
    public $form;
    public $is_active;
    public $perPage =10;
    public $search;
    public $services, $agents;
    public $serviceTransaction;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showServiceTransaction'];

    public function mount()
    {
        $this->page_title = __('admin.service_transactions');
        $this->services = Service::query()->get();
        $this->agents = Agent::query()->get();
    }

    public function updatedFormServiceId()
    {
        if(isset($this->form['service_id']) && !empty($this->form['service_id'])){

            $service = Service::query()->find($this->form['service_id']);
            $setting = Setting::query()->first();

            $value = (int) filter_var($setting->vat_rate, FILTER_SANITIZE_NUMBER_INT);

            $amount = $service->dubai_fee + $service->service_fee + ($value / 100 * $service->service_fee);
            $vat = $value / 100 * $service->service_fee;
            $this->form['amount'] = $amount;
            $this->form['vat'] = $vat;
        }
    }
    public function updatedFormPassportNo()
    {
        if ($this->form['passport_no'] !== '') {
            $serviceTransaction = ServiceTransaction::where('passport_no', $this->form['passport_no'])->first();
            if ($serviceTransaction) {
                $this->form['name'] = $serviceTransaction->name;
                $this->form['surname'] = $serviceTransaction->surname;
            }
        }
    }
    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function showServiceTransaction($id)
    {
        $this->serviceTransaction = ServiceTransaction::query()->find($id);
        $this->form = $this->serviceTransaction->toArray();

        $this->emit("showServiceTransactionModal", $id);
    }

    public function resetData()
    {
        $this->reset(['search']);
    }
    public function getRecords()
    {
        return ServiceTransaction::query()
            ->when($this->search, function ($query) {
                $query->whereHas('agent',function ($query){
                    $query->where('name', 'like', '%'.$this->search.'%');
                })->orWhereHas('service', function ($query){
                    $query->where('name', 'like', '%'.$this->search.'%');
                })->orWhere('passport_no', 'like', '%'.$this->search.'%')
                    ->orWhere('name', 'like', '%'.$this->search.'%')
                    ->orWhere('surname', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate();
    }

    public function update()
    {
        $this->validate();
        $data = Arr::except($this->form,['id', 'updated_at', 'created_at']);

        ServiceTransaction::query()->find($this->form['id'])->update($data);

        $this->form = [];
        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.service_transactions'));
    }

    public function store()
    {
        $this->validate();
        ServiceTransaction::query()->create($this->form);
        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.service_transactions'));
    }

    public function getRules(){
        return [
            'form.service_id'=>['required'],
            'form.agent_id' => ['nullable'],
            'form.passport_no' => ['nullable'],
            'form.name' => ['required', 'string'],
            'form.surname' => ['required', 'string'],
            'form.notes' => ['nullable'],
            'form.vat' => ['nullable'],
        ];
    }
    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.service-transaction.index', compact('records'))->layout('layouts.admin');
    }
}
