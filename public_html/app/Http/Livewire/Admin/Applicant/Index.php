<?php

namespace App\Http\Livewire\Admin\Applicant;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Agent;
use App\Models\Applicant;
use App\Models\VisaType;
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
    public $applicant;
    public $agents;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showApplicant'];

    public function mount()
    {
        $this->page_title = __('admin.applicant');
        $this->agents = Agent::query()->get();
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function showApplicant($id)
    {
        $this->applicant = Applicant::query()->find($id);
        $this->form = $this->applicant->toArray();

        $this->emit("showApplicantModal", $id);
    }

    public function getRecords()
    {
        return Applicant::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('surname', 'like', '%'.$this->search.'%')
                        ->orWhere('passport_no', 'like', '%'.$this->search.'%')
                        ->orWhere('address', 'like', '%'.$this->search.'%')
                        ->orWhereHas('agent', function ($query){
                            $query->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->latest()
            ->paginate(50);
    }

    public function emptyForm()
    {
        $this->form =[];
    }

    public function update()
    {
        $this->validate();
        $data = Arr::except($this->form,['id', 'updated_at', 'created_at']);

        Applicant::query()->find($this->form['id'])->update($data);

        $this->form = [];
        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.applicants'));
    }

    public function resetData()
    {
        $this->reset(['search']);
    }
    public function store()
    {
        $this->validate();
        Applicant::query()->create($this->form);
        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.applicants'));
    }

    public function getRules(){
        return [
            'form.agent_id' =>['nullable', 'exists:agents,id'],
            'form.name'=>'required|max:500',
            'form.surname'=>'required|max:500',
            'form.address'=>'required|max:500',
            'form.passport_no' => [
                'required',
                'max:500',
                Rule::unique('applicants', 'passport_no')->ignore($this->applicant->id??null),
            ],
            'form.passport_expiry'=>'required|date',
        ];
    }

    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.applicant.index', compact('records'))->layout('layouts.admin');
    }
}
