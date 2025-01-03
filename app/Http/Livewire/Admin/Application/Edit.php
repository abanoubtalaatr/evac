<?php

namespace App\Http\Livewire\Admin\Application;

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
use Illuminate\Support\Arr;
use Livewire\Component;
use function App\Helpers\vatRate;

class Edit extends Component
{
    use ValidationTrait;
    public $name;
    public $form;
    public $is_active;
    public $perPage = 10;
    public $search;
    public $application;
    public $visaTypes, $visaProviders, $travelAgents;
    public $isChecked = false, $showAlertBlackList = false;
    public $passportNumber = [];
    public $passportApplications;
    public $numberOfDaysToCheckVisa = 90;
    public $isEdit = false;
    public $agentName = '';
    public $previousPage;
    protected $paginationTheme = 'bootstrap';
    public $isExpiryInPast = false;
    public $page_title;
    public  $expiryDate;

    protected $listeners = ['showApplication'];


    public function mount(Application $application)
    {
        $this->page_title = __('admin.applications');
        $this->visaTypes = VisaType::query()->get();
        $this->visaProviders = VisaProvider::query()->get();
        $this->travelAgents = Agent::query()->where('is_active', 1)->get();

        $application = Application::query()->find(request()->application);
        $this->form = $application->toArray();
        $this->passportNumber = $application->passport_no;
        $this->form['expiry_date'] = Carbon::parse($application->expiry_date)->format('Y-m-d');
        $this->form['created_at'] = Carbon::parse($application->created_at)->format('Y-m-d');


        $this->application = $application;
        $this->page_title = __('admin.applications_edit');
        if ($application->travel_agent_id) {
            $agent = Agent::query()->find($application->travel_agent_id);
            $this->agentName = $agent->name;
            $this->isChecked = true;
            $this->isEdit = true;
        }
        $this->previousPage = url()->previous();
    }


    public function chooseTravelAgent()
    {
        $this->isChecked = !$this->isChecked;
    }


    public function updatedFormVisaTypeId()
    {
        $vatRate = vatRate($this->form['visa_type_id']);
        $visaType = VisaType::query()->find($this->form['visa_type_id']);
        $amount = $visaType->dubai_fee + $visaType->service_fee + $vatRate;

        $this->form['vat'] = $vatRate;
        $this->form['amount'] = $amount;
        $this->form['dubai_fee'] = $visaType->dubai_fee;
        $this->form['service_fee'] = $visaType->service_fee;
    }
    public function store()
    {
        $this->validate();

        if ($this->checkPassportInBlackList()) {
            $this->emit('openBlackListModal');
            return;
        }

        if ($this->checkExpiryPassport()) {
            $this->emit('showExpiryPopup');
            return;
        }

        if ($this->checkPassportHasMoreThanOneApplication()) {
            $this->emit('showMultipleApplicationsPopup');
            return;
        }

        $this->save();
    }

    public function checkPassportHasMoreThanOneApplication()
    {
        $settings = Setting::query()->first();
        $numberOfDaysToCheckVisa = 90;
        if ($settings) {
            $numberOfDaysToCheckVisa = $settings->no_of_days_to_check_visa;
        }
        $this->numberOfDaysToCheckVisa = $numberOfDaysToCheckVisa;

        $previousApplications = Application::where('passport_no', $this->form['passport_no'])
            ->where('created_at', '>', now()->subDays($numberOfDaysToCheckVisa))
            ->get();


        if ($previousApplications->count() > 1) {
            $this->passportApplications = $previousApplications;
            return true;
        }
        return false;
    }
    public function checkExpiryPassport(): bool
    {
        $expiryDateTime = new \DateTime($this->form['expiry_date']);
        $currentDateTime = now();

        // Calculate the signed difference in days
        $difference = $expiryDateTime->getTimestamp() - $currentDateTime->getTimestamp();
        $daysDifference = (int) floor($difference / 86400); // Convert seconds to days

        $this->expiryDate = $expiryDateTime->format('Y-m-d'); // Save to a public property

        // Default threshold
        $numberOfExpireDays = 180;

        // Check settings for a custom threshold
        $settings = Setting::query()->first();
        if ($settings && $settings->passport_expiry_days) {
            $numberOfExpireDays = $settings->passport_expiry_days;
        }

        if ($daysDifference < 0) {
            $this->isExpiryInPast = true;
        }

        // Check if the expiry date is in the past


        // Check if expiry date is in the past or within the threshold
        if ($daysDifference < $numberOfExpireDays) {
            return true;
        }

        return false;
    }

    public function save()
    {
        $this->validate();
        $data = $this->form;

        if (isset($this->form['agent_id']) && ($this->form['agent_id'] == 'nr' || $this->form['agent_id'] == 'no_result')) {
            $this->addError('form.agent_id', 'Must enter a valid agent');
            $this->emit('closePopups');

            return;
        }

        if (isset($data['agent_id'])) {
            $data['travel_agent_id'] = $data['agent_id'];
            unset($data['agent_id']);
        }

        if (isset($data['agent_id']) && is_null($data['agent_id'])) {
            unset($data['agent_id']);
            $data['travel_agent_id'] = null;
        }

        (new ApplicantService())->update($data);
        $this->application->update(Arr::except($data, ['updated_at']));
        session()->flash('success', __('admin.edit_successfully'));

        $this->redirect($this->previousPage);
    }

    public function checkPassportInBlackList()
    {
        $blackList = BlackListPassport::query()->where('passport_number', $this->form['passport_no'])->first();

        if ($blackList) {
            return true;
        }
        return false;
    }

    public function updatedFormPassportNo()
    {
        $existingPassport = Applicant::where('passport_no', strtolower($this->form['passport_no']))->latest()->first();
        if ($existingPassport) {
            $this->form['expiry_date'] = Carbon::parse($existingPassport->passport_expiry)->format('Y-m-d');
            $this->form['first_name'] = $existingPassport->name;
            $this->form['last_name'] = $existingPassport->surname;
        } else {
            $this->form['expiry_date'] = null;
            $this->form['first_name'] = null;
            $this->form['last_name'] = null;
        }
    }

    public function getRules()
    {
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
            'form.amount' => 'nullable|numeric',
            'form.created_at' => ['nullable']
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

    public function render()
    {
        return view('livewire.admin.application.edit')->layout('layouts.admin');
    }
}
