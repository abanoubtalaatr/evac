<?php

namespace App\Http\Livewire\Admin\Application;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Agent;
use App\Models\Application;
use App\Models\BlackListPassport;
use App\Models\Setting;
use App\Models\VisaProvider;
use App\Models\VisaType;
use Carbon\Carbon;
use Livewire\Component;

class Create extends Component
{
    use ValidationTrait;
    public $name;
    public $form;
    public $is_active;
    public $perPage =10;
    public $search;
    public $application;
    public $visaTypes, $visaProviders, $travelAgents ;
    public $isChecked = false, $showAlertBlackList = false;
    public $passportNumber = [];
    public $passportApplications;
    public $numberOfDaysToCheckVisa=90;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showApplication'];

    public function mount()
    {
        $this->page_title = __('admin.applications');
        $this->visaTypes = VisaType::query()->get();
        $this->visaProviders = VisaProvider::query()->get();
        $this->travelAgents = Agent::query()->where('is_active', 1)->get();
    }


    public function chooseTravelAgent()
    {

        $this->isChecked = !$this->isChecked;
    }

    public function updatedFormVisaTypeId()
    {
        $setting = Setting::query()->first();

        $value = (int) filter_var($setting->vat_rate, FILTER_SANITIZE_NUMBER_INT);

        $visaType = VisaType::query()->find($this->form['visa_type_id']);
        $amount = $visaType->dubai_fee + $visaType->service_fee + ($value / 100 * $visaType->service_fee);

        $this->form['amount'] = $amount;
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

    public function checkPassportHasMoreThanOneApplication()
    {
        $settings = Setting::query()->first();
        $numberOfDaysToCheckVisa = 90;
        if($settings) {
            $numberOfDaysToCheckVisa = $settings->no_of_days_to_check_visa;
        }
        $this->numberOfDaysToCheckVisa =$numberOfDaysToCheckVisa;

        $previousApplications = Application::where('passport_no', $this->form['passport_no'])
            ->where('created_at', '>', now()->subDays($numberOfDaysToCheckVisa))
            ->get();


        if ($previousApplications->count() > 1) {
            $this->passportApplications = $previousApplications;
            return true;
        }
        return false;
    }
    public function checkExpiryPassport()
    {
        $expiryDateTime = new \DateTime($this->form['expiry_date']);
        $difference = now()->diff($expiryDateTime)->days;

        if ($difference < 180) {
           return true;
        }
        return false;
    }

    public function save()
    {
        $this->validate();
        $data = $this->form;

        $today = Carbon::now()->format('dmY');
        $currentSerial = Application::where('application_ref', 'like', 'EVLB/' . $today . '/%')
            ->max('application_ref');
        $nextSerial = $currentSerial ? (int)substr($currentSerial, -4) + 1 : 1;
        $applicationReference = 'EVLB/' . $today . '/' . str_pad($nextSerial, 4, '0', STR_PAD_LEFT);

        $data['application_ref'] = $applicationReference;

        $application = Application::query()->create($data);
        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.applications.appraisal'));
    }

    public function checkPassportInBlackList()
    {
        $blackList = BlackListPassport::query()->where('passport_number', $this->form['passport_no'])->first();

        if($blackList) {
            return true;
        }
        return false;
    }

    public function checkPassportNumber()
    {
        $existingPassport = Application::where('passport_no', $this->passportNumber)->first();

        if ($existingPassport) {
            $this->form['passport_no'] = $existingPassport->passport_no??$this->passportNumber;
            $this->form['expiry_date'] = Carbon::parse($existingPassport->expiry_date)->format('Y-m-d');
            $this->form['first_name'] = $existingPassport->first_name;
            $this->form['last_name'] = $existingPassport->last_name;
        }else{
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
            'form.title' => ['required', 'in:Mr,Mrs,Ms'],
            'form.notes' => 'required|max:500',
            'form.amount' => 'required|numeric|max:500',
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
    public function updatedFormPassportNo()
    {
        $this->searchResults = []; // Clear previous search results

        if ($this->form['passport_no'] !== '') {
            $foundUser = Application::where('passport_no', $this->form['passport_no'])->first();
            if ($foundUser) {
                $this->form['first_name'] = $foundUser->first_name;
                $this->form['last_name'] = $foundUser->last_name;
            }
        }
    }


    public function render()
    {
        return view('livewire.admin.application.create')->layout('layouts.admin');
    }
}
