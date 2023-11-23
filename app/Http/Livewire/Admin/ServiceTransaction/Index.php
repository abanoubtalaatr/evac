<?php

namespace App\Http\Livewire\Admin\ServiceTransaction;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Agent;
use App\Models\Application;
use App\Models\Service;
use App\Models\ServiceTransaction;
use App\Models\Setting;
use App\Models\VisaType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Browsershot\Browsershot;
use function App\Helpers\recalculateVat;
use function App\Helpers\recalculateVatInServiceTransaction;

class Index extends Component
{
    use WithPagination;
    use ValidationTrait;

    public $name, $surname, $agent,$service,$from,$to, $passport;
    public $form;
    public $is_active;
    public $perPage =10;
    public $services, $agents;
    public $serviceTransaction, $formInvoice;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showServiceTransaction', 'showServiceTransactionInvoice'];

    public function mount()
    {
        $this->page_title = __('admin.service_transactions');
        $this->services = Service::query()->get();
        $this->agents = Agent::query()->orderBy('name')->get();
        if(!$this->serviceTransaction){
            $this->form['payment_method'] = 'invoice';
        }
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

    public function updatedFormAmount()
    {
        if(isset($this->form['service_id'])) {
            $service = Service::query()->find($this->form['service_id']);
            $vat =  recalculateVatInServiceTransaction($service->service_fee,$this->form['amount'], $service->dubai_fee,$this->form['vat']);
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
    public function showServiceTransactionInvoice($id)
    {
        $this->serviceTransaction = ServiceTransaction::query()->find($id);
        $this->formInvoice['payment_method'] = $this->serviceTransaction->payment_method;
        $this->formInvoice['amount'] = $this->serviceTransaction->amount;

        $this->emit("showServiceTransactionInvoiceModal", $id);
    }

    public function emptyForm()
    {
        $this->form =[];
    }
    public function destroy(ServiceTransaction $serviceTransaction)
    {
        $serviceTransaction->update(['status' => 'deleted']);
    }
    public function updateInvoice()
    {
        $this->serviceTransaction->update([
            'payment_method' => $this->formInvoice['payment_method'],
            'amount' => $this->formInvoice['amount'],
        ]);


        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.service_transactions'));

    }
    public function resetData()
    {
        $this->reset(['agent', 'service', 'name', 'surname', 'from', 'to']);
    }
    public function getRecords()
    {
        return ServiceTransaction::query()
            ->when(!empty($this->name), function ($query) {
                $query->where('name', 'like', '%'.$this->name.'%');
            })->when(!empty($this->surname), function ($query){
                $query->where('surname', 'like', '%'.$this->surname.'%');
            })->when(!empty($this->passport), function ($query){
                $query->where('passport_no', 'like', '%'.$this->passport.'%');
            })->when(!empty($this->agent), function ($query){
                $query->whereHas('agent', function ($query){
                    $query->where('name', 'like', '%'.$this->agent.'%');
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
        $today = Carbon::now()->format('dmY');
        $currentSerial = ServiceTransaction::where('service_ref', 'like', 'EVLB/' . $today . '/%')
            ->max('service_ref');
        $nextSerial = $currentSerial ? (int)substr($currentSerial, -4) + 1 : 1;
        $applicationReference = 'EVLB/' . $today . '/' . str_pad($nextSerial, 4, '0', STR_PAD_LEFT);
        $this->form['service_ref'] = $applicationReference;

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
