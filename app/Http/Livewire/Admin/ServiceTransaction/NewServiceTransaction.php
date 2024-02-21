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

class NewServiceTransaction extends Component
{
    use WithPagination;
    use ValidationTrait;

    public $name, $surname, $agent,$service,$from,$to, $passport;
    public $form;
    public $is_active;
    public $perPage =10;
    public $services, $agents;
    public $serviceTransaction, $formInvoice;
    public $status = "new", $showSendEmail,$serviceTransactionThatSendByEmail, $email;

    protected $paginationTheme = 'bootstrap';
    public $showModal = false;
    protected $listeners = ['showServiceTransaction', 'showServiceTransactionInvoice'];

    public function mount()
    {
        $this->page_title = __('admin.new_service_transactions');
        $this->services = Service::query()->get();
        if(isOwner()){
            $this->agents = Agent::query()->isActive()->orderBy('name')->get();
        }else{
            $this->agents = Agent::owner()->isActive()->orderBy('name')->get();

        }
        if(!$this->serviceTransaction){
            $this->form['payment_method'] = 'invoice';
        }

        $this->form['created_at'] = Carbon::parse(now())->format('Y-m-d');
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
            $this->form['service_fee'] = $service->service_fee;
        }
    }

    public function downloadCSV($id)
    {
        $application = \App\Models\ServiceTransaction::query()->withoutGlobalScope('excludeDeleted')->find($id);
        return Excel::download(new ReceiptServiceTransactionExport($application), 'serviceReceipt.csv');
    }

    public function send($id= null)
    {
        if(!empty($this->email)){
            Mail::to($this->email)->send(new ReceiptServiceTransactionMail($this->serviceTransactionThatSendByEmail));
            $this->email = null;
            $this->showSendEmail = !$this->showSendEmail;
        }
    }

    public function showCreateModal()
    {
        $this->emptyForm();
        $this->form['created_at'] = Carbon::parse(now())->format('Y-m-d');
        $this->showModal = true;
    }

    public function hideCreateModal()
    {
        return redirect()->to(route('admin.service_transactions.new'));

    }

    public function toggleShowModal($id =null)
    {
        $this->serviceTransactionThatSendByEmail = ServiceTransaction::withoutGlobalScope('excludeDeleted')->find($id);
        $this->showSendEmail = !$this->showSendEmail;
    }
    public function emptyForm()
    {
        $this->form = [];
        $this->resetValidation();
    }

//    public function updatedFormAmount()
//    {
//        if(isset($this->form['service_id'])) {
//            $service = Service::query()->find($this->form['service_id']);
//            $vat = (new InvoiceService())->recalculateVat($this->form['amount'], $service->dubai_fee);
//            $serviceFee = (new InvoiceService())->recalculateServiceFee($this->form['amount'], $service->dubai_fee);
//
//            $this->form['vat'] = $vat;
//            $this->form['service_fee'] = $serviceFee;
//            $this->form['amount'] = $this->form['vat'] + $this->form['service_fee']+ $service->dubai_fee;
//        }
//    }
    public function updateAmount()
    {
        if(isset($this->form['service_id'])) {
            $service = Service::query()->find($this->form['service_id']);
            $vat = (new InvoiceService())->recalculateVat($this->form['amount'], $service->dubai_fee);
            $serviceFee = (new InvoiceService())->recalculateServiceFee($this->form['amount'], $service->dubai_fee);

            $this->form['vat'] = $vat;
            $this->form['service_fee'] = $serviceFee;
            $this->form['amount'] = $this->form['vat'] + $this->form['service_fee']+ $service->dubai_fee;

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
        $this->serviceTransaction = ServiceTransaction::query()->withoutGlobalScope('excludeDeleted')
            ->find($id);
        $this->form = $this->serviceTransaction->toArray();

        $this->form['created_at'] = Carbon::parse($this->serviceTransaction->created_at)->format('Y-m-d');

        $this->emit("showServiceTransactionModal", $id);
    }
    public function showServiceTransactionInvoice($id)
    {
        $this->serviceTransaction = ServiceTransaction::query()->withoutGlobalScope('excludeDeleted')->find($id);
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

        return redirect()->to(route('admin.service_transactions.new'));

    }
    public function resetData()
    {
        $this->reset(['agent', 'service', 'name', 'surname', 'from', 'to']);
    }

    public function searchPayment()
    {
        // Fetch data based on search parameters
        $this->getRecords();
    }
    public function getRecords()
    {
        $query = ServiceTransaction::query()
            ->withoutGlobalScope('excludeDeleted')
            ->when(!empty($this->name), function ($query) {
                $query->where('name', 'like', '%' . $this->name . '%');
            })
            ->when(!empty($this->surname), function ($query) {
                $query->where('surname', 'like', '%' . $this->surname . '%');
            })
            ->when(!empty($this->passport), function ($query) {
                $query->where('passport_no', 'like', '%' . $this->passport . '%');
            })
            ->when(!empty($this->service), function ($query) {
                $query->whereHas('service', function ($query) {
                    $query->where('name', 'like', '%' . $this->service . '%');
                });
            })
            ->when(!empty($this->from) && !empty($this->to), function ($query) {
                $query->whereDate('created_at', '>=', $this->from)
                    ->whereDate('created_at', '<=', $this->to);
            })
            ->when(isset($this->status), function ($query) {
                if ($this->status == "new") {
                    // If status is "new", filter transactions created today
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($this->status == "all") {
                    if (!empty($this->agent) && $this->agent != 'no_result') {
                        // If agent is provided, get all service transactions for that agent
                        $query->where('agent_id', $this->agent);
                    }
                } else {
                    // If a specific status is selected, filter by both status and agent if provided
                    $query->where('status', $this->status);
                    if (!empty($this->agent) && $this->agent != 'no_result') {
                        $query->where('agent_id', $this->agent);
                    }
                }
            });

        return $query->latest()->paginate(50);
    }

    public function unDestroy( $serviceTransaction)
    {
        $serviceTransaction = ServiceTransaction::query()->withoutGlobalScope('excludeDeleted')->find($serviceTransaction);

        $serviceTransaction->update(['status' => null]);
    }

    public function update()
    {
        $this->validate();
        $data = Arr::except($this->form,['id', 'updated_at']);

        $this->updateAmount();

        ServiceTransaction::query()->withoutGlobalScope('excludeDeleted')->find($this->form['id'])->update($this->form);

        $this->form = [];
        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.service_transactions.new'));
    }

    public function store()
    {
        $this->validate();
// Assuming $today is already defined in your code as the current date in ddmmyyyy format
        $today = date('dmY');

        if(isset($this->form['created_at'])){
            $today = Carbon::parse($this->form['created_at'])->format('dmY');
        }

        $currentSerial = ServiceTransaction::where('service_ref', 'like', 'EVLB/' . $today . '/%')
            ->max('service_ref');

        $nextSerial = $currentSerial ? (int)substr($currentSerial, -3) + 1 : 1;
        $nextSerialPadded = str_pad($nextSerial, 3, '0', STR_PAD_LEFT);

        $applicationReference = 'EVLB/' . $today . '/S' . $nextSerialPadded;
        $this->form['service_ref'] = $applicationReference;

        $service = Service::query()->find($this->form['service_id']);
        $this->form['dubai_fee'] = $service->dubai_fee;

        if (!isset($this->form['service_fee'])){
            $this->form['service_fee'] = $service->service_fee;
        }

        $this->form['status']=  "new";
//        if(isset($this->from['created_at'])){
//            $this->from['updated_at'] = $this->from['created_at'];
//        }

        $this->updateAmount();

        ServiceTransaction::query()->create($this->form);

        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.service_transactions.new'));
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
            'form.created_at' => ['nullable'],
        ];
    }
    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.service-transaction.new-service-transaction', compact('records'))->layout('layouts.admin');
    }
}
