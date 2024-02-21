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
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
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
    public $application;
    public $visaTypes, $visaProviders, $travelAgents ;
    public $isChecked = false, $showAlertBlackList = false;
    public $passportNumber;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showApplication'];

    public function mount()
    {
        $this->page_title = __('admin.applications');
        $this->visaTypes = VisaType::query()->get();
        $this->visaProviders = VisaProvider::query()->get();
        $this->travelAgents = Agent::query()->where('is_active', 1)->get();
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {
        return Application::query()
            ->when(!empty($this->is_active), function ($query) {
                if($this->is_active == "1") {
                    return  $query->where('is_active', "1");
                }
                if($this->is_active == 'not_active') {
                    return  $query->where('is_active', "0");
                }
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('contact_name', 'like', '%'.$this->search.'%')
                        ->orWhere('owner_name', 'like', '%'.$this->search.'%')
                        ->orWhere('name', 'like', '%'.$this->search.'%')
                        ->orWhere('telephone', 'like', '%'.$this->search.'%')
                        ->orWhere('mobile', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate();
    }

    public function resetData()
    {
        $this->reset(['is_active', 'search']);
    }


    public function update()
    {
        $this->validate();
        $data = Arr::except($this->form,['id', 'updated_at', 'created_at', 'is_active']);

        $application = Application::query()->find($this->form['id'])->update($data);

        $this->form = [];
        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.applications'));
    }


    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.application.index',  compact('records'))->layout('layouts.admin');
    }
}
