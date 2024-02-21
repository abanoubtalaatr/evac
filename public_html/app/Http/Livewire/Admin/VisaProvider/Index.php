<?php

namespace App\Http\Livewire\Admin\VisaProvider;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Agent;
use App\Models\VisaProvider;
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
    public $visaProvider;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showVisaProvider'];

    public function mount()
    {
        $this->page_title = __('admin.visa_providers');
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function showVisaProvider($id)
    {
        $this->visaProvider = VisaProvider::query()->find($id);
        $this->form = $this->visaProvider->toArray();

        $this->emit("showVisaProviderModal", $id);
    }

    public function emptyForm()
    {
        $this->form =[];
        $this->resetValidation();
    }

    public function makeDefault(VisaProvider $visaProvider)
    {
        VisaProvider::where('is_default', 1)->update(['is_default' => 0]);

        $visaProvider->update(['is_default' => 1]);
    }
    public function getRecords()
    {
        return VisaProvider::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(50);
    }

    public function update()
    {
        $this->validate();
        $data = Arr::except($this->form,['id', 'updated_at', 'created_at']);

        VisaProvider::query()->find($this->form['id'])->update($data);

        $this->form = [];
        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.visa_providers'));
    }

    public function store()
    {
        $this->validate();
        $visaProvider = VisaProvider::query()->create($this->form);

        if(VisaProvider::query()->count() == 1){
            $visaProvider->update(['is_default' => 1]);
        }

        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.visa_providers'));
    }

    public function getRules(){
        return [
            'form.name'=>'required|max:500',
        ];
    }
    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.visa-provider.index', compact('records'))->layout('layouts.admin');
    }
}
