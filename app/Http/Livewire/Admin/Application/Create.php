<?php

namespace App\Http\Livewire\Admin\Application;

use App\Http\Livewire\Traits\Admin\Application\ApplicationChecks;
use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Agent;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\BlackListPassport;
use App\Models\Setting;
use App\Models\VisaProvider;
use App\Models\VisaType;
use App\Services\ApplicantService;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Livewire\Component;
use function App\Helpers\createApplicant;
use function App\Helpers\vatRate;

class Create extends Component
{
    use ValidationTrait;
    use ApplicationChecks;

    public $name;
    public $form;
    public $is_active;
    public $perPage =10;
    public $search;
    public $application;
    public $visaTypes, $visaProviders, $travelAgents ;
    public $isChecked = false, $showAlertBlackList = false;
    public $passportNumber;
    public $passportApplications;
    public $numberOfDaysToCheckVisa = 90;
    public $defaultVisaTypeId;
    protected $paginationTheme = 'bootstrap';
    public $showPrint = false;
    public $record;
    public $searchResults = [];



    protected $listeners = ['showApplication'];

    public function mount()
    {
        $this->page_title = __('admin.applications');
        $this->visaTypes = VisaType::query()->get();
        $this->visaProviders = VisaProvider::query()->get();
        $this->travelAgents = Agent::query()->where('is_active', 1)->get();
        $defaultVisaType = VisaType::query()->where('is_default', 1)->first();
        $defaultVisaProvider = VisaProvider::query()->where('is_default', 1)->first();
        $this->form['payment_method'] ="invoice";
        if($defaultVisaType){
            $this->defaultVisaTypeId =$defaultVisaType->id;
            $this->form['visa_type_id'] = $defaultVisaType->id;
            $this->form['service_fee'] = $defaultVisaType->service_fee;
            $this->form['dubai_fee'] = $defaultVisaType->dubai_fee;
            $this->updateAmount();
        }
        if($defaultVisaProvider){
            $this->form['visa_provider_id'] = $defaultVisaProvider->id;
        }
    }

    public function chooseTravelAgent()
    {
        $this->isChecked = !$this->isChecked;
    }

    public function updateAmount()
    {
        $vatRate = vatRate($this->form['visa_type_id']);
        $visaType = VisaType::query()->find($this->form['visa_type_id']);
        $amount = $visaType->dubai_fee + $visaType->service_fee + $vatRate;

        $this->form['vat'] = $vatRate;
        $this->form['amount'] = $amount;
        $this->form['dubai_fee'] = $visaType->dubai_fee;
        $this->form['service_fee'] = $visaType->service_fee;
    }

    public function updatedFormVisaTypeId()
    {
        $this->updateAmount();
    }

    public function store()
    {
        $this->validate();

        if($this->checkPassportInBlackList()) {
            $this->emit('openBlackListModal');
            return;
        }

        if ($this->checkExpiryPassport()){
            $this->emit('showExpiryPopup');
            return;
        }

        if($this->checkPassportHasMoreThanOneApplication()) {
            $this->emit('showMultipleApplicationsPopup');
            return;
        }

        $this->save();
    }
    public function save()
    {
        $this->validate();
        $data = $this->form;

        if(isset($this->form['agent_id']) && ($this->form['agent_id'] == 'nr' || $this->form['agent_id'] == 'no_result')) {
            $this->addError('form.agent_id', 'Must enter a valid agent');
            $this->emit('closePopups');

            return;
        }

        $today = Carbon::now()->format('dmY');
        $currentSerial = Application::where('application_ref', 'like', 'EVLB/' . $today . '/%')
            ->max('application_ref');
        $nextSerial = $currentSerial ? (int)substr($currentSerial, -4) + 1 : 1;
        $applicationReference = 'EVLB/' . $today . '/' . str_pad($nextSerial, 4, '0', STR_PAD_LEFT);

        $data['application_ref'] = $applicationReference;
        if(isset($this->form['agent_id'])) {
            $data['travel_agent_id'] = $this->form['agent_id'];
            unset($data['agent_id']);
        }

       $applicant = (new ApplicantService())->create($data);

        $visaType = VisaType::query()->find($this->form['visa_type_id']);
        $data['applicant_id'] = $applicant->id;

        $application = Application::query()->create($data);

        $this->showPrint = true;
        $this->record = $application;

        $this->resetData();
        $this->updateAmount();
        $this->emit('closePopups');
    }

    public function resetData()
    {
        $this->form['notes'] = null;
        $this->form['passport_no'] = null;
        $this->form['first_name'] = null;
        $this->form['last_name'] = null;
        $this->form['expiry_date'] = null;
        $this->form['travel_agent_id'] =null;
        $this->form['agent_id'] = null;
    }

    public function checkPassportNumber()
    {

        // Convert the input passport number to uppercase for case-insensitive search
        $passportNumber = $this->passportNumber;

        $existingPassport = Application::where('passport_no', $passportNumber)->first();

        if ($existingPassport) {
            $this->form['passport_no'] = $existingPassport->passport_no ?? $passportNumber;
            $this->form['expiry_date'] = Carbon::parse($existingPassport->expiry_date)->format('Y-m-d');
            $this->form['first_name'] = $existingPassport->first_name;
            $this->form['last_name'] = $existingPassport->last_name;

        } else {

            $this->form['passport_no'] = $this->passportNumber;
        }

    }

    public function getRules(){
        return [
            'form.visa_type_id' => 'required|max:500',
            'form.visa_provider_id' => 'required|max:500',
            'form.passport_no' => 'required',
            'form.expiry_date' => 'required|date',
            'form.travel_agent_id' => 'nullable',
            'form.first_name' => 'required',
            'form.last_name' => 'required',
            'form.title' => ['nullable', 'in:Mr,Mrs,Ms'],
            'form.notes' => 'nullable|max:500',
            'form.amount' => 'nullable|numeric|max:500',
        ];
    }
    public function showAgent($id)
    {
        $this->form = [];
        $this->applicationId = $id;
        $this->application = Application::query()->find($id);

        $this->form = $this->application->toArray();

        $this->emit("showApplicationModal", $id);
    }

    public function resetApplication()
    {
        return redirect()->to(route('admin.applications.store'));
    }

    public function updatedFormAmount()
    {
        $visaType = VisaType::query()->find($this->form['visa_type_id']);
        $newServiceFee = (new InvoiceService())->recalculateServiceFee($this->form['amount'], $visaType->dubai_fee);
        $this->form['vat'] = (new InvoiceService())->recalculateVat($this->form['amount'], $visaType->dubai_fee);
        $this->form['service_fee'] = $newServiceFee;
    }
    public function updatedFormPassportNo()
    {

        $this->searchResults = []; // Clear previous search results

        if ($this->form['passport_no'] !== '') {

        $existingPassport = Applicant::where('passport_no', strtolower($this->form['passport_no']))->latest()->first();
            if ($existingPassport) {

                $this->form['expiry_date'] = Carbon::parse($existingPassport->passport_expiry)->format('Y-m-d');
                $this->form['first_name'] = $existingPassport->name;
                $this->form['last_name'] = $existingPassport->surname;

            }else{
                $this->form['expiry_date'] =null;
                $this->form['first_name'] = null;
                $this->form['last_name']= null;
            }
        }
    }


    public function selectTravelAgent($agentId)
    {
        $this->form['travel_agent_id'] = $agentId;
        $agentName = Agent::query()->find($agentId)->name;
        $this->search = ''; // Clear the search input
        $this->emit('agentSelected', $agentId, $agentName);
    }

    public function render()
    {
        return view('livewire.admin.application.create')->layout('layouts.admin');
    }
}
