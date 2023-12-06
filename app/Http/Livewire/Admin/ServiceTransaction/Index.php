<?php

namespace App\Http\Livewire\Admin\ServiceTransaction;

use App\Exports\ReceiptApplicationExport;
use App\Exports\ReceiptServiceTransactionExport;
use App\Http\Livewire\Traits\ValidationTrait;
use App\Mail\ReceiptApplicationsMail;
use App\Mail\ReceiptServiceTransactionMail;
use App\Models\Agent;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\Service;
use App\Models\ServiceTransaction;
use App\Models\Setting;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use function App\Helpers\isOwner;

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
    public $status = null, $showSendEmail,$serviceTransactionThatSendByEmail, $email;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showServiceTransaction', 'showServiceTransactionInvoice'];

    public function mount()
    {
        $this->page_title = __('admin.service_transactions');
        $this->services = Service::query()->get();
        if(isOwner()){
            $this->agents = Agent::query()->orderBy('name')->get();
        }else{
            $this->agents = Agent::owner()->orderBy('name')->get();

        }
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

    public function downloadCSV($id)
    {
        $application = \App\Models\ServiceTransaction::query()->find($id);
        return Excel::download(new ReceiptServiceTransactionExport($application), 'serviceReceipt.csv');
    }

    public function send()
    {
        if(!empty($this->email)){
            Mail::to($this->email)->send(new ReceiptServiceTransactionMail($this->serviceTransactionThatSendByEmail));
            $this->email = null;
            $this->showSendEmail = !$this->showSendEmail;
        }
    }

    public function toggleShowModal($id)
    {
        $this->serviceTransactionThatSendByEmail = ServiceTransaction::find($id);
        $this->showSendEmail = !$this->showSendEmail;
    }
    public function emptyForm()
    {
        $this->form = [];
        $this->resetValidation();
    }

    public function updatedFormAmount()
    {
        if(isset($this->form['service_id'])) {
            $service = Service::query()->find($this->form['service_id']);
            $vat = (new InvoiceService())->recalculateVat($this->form['amount'], $service->dubai_fee);
            $serviceFee = (new InvoiceService())->recalculateServiceFee($this->form['amount'], $service->dubai_fee);

            $this->form['vat'] = $vat;
            $this->form['service_fee'] = $serviceFee;
        }
    }
    public function updatedFormPassportNo()
    {
        if ($this->form['passport_no'] !== '') {
            $applicant = Applicant::where('passport_no', $this->form['passport_no'])->first();
            if ($applicant) {
                $this->form['name'] = $applicant->name;
                $this->form['surname'] = $applicant->surname;
            }else{
                $this->form['name'] = null;
                $this->form['surname'] = null;
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
                if($this->agent =='no_result'){
                    $query->where('agent_id', '>', 0);
                }else{
                    $query->where('agent_id', $this->agent);
                }
            })->when(!empty($this->service), function ($query){
                $query->whereHas('service', function ($query){
                    $query->where('name', 'like', '%'.$this->service.'%');
                });
            })->when(!empty($this->from) && !empty($this->to), function ($query) {
                $query->whereBetween('created_at', [$this->from, $this->to]);
            })
            ->when(is_null($this->status), function ($query){
                $query->where('status', null);
            })
            ->when(!is_null($this->status), function ($query){
                if($this->status =='deleted'){
                    $query->where('status', 'deleted');
                }else{
                    $query->where('status', null);
                }
            })
            ->latest()
            ->paginate(50);
    }

    public function unDestroy(ServiceTransaction $serviceTransaction)
    {
        $serviceTransaction->update(['status' => null]);
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

        $service = Service::query()->find($this->form['service_id']);
        $this->form['dubai_fee'] = $service->dubai_fee;
        $this->form['service_fee'] = $service->service_fee;

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
