<?php

namespace App\Http\Livewire\Admin\Service;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Service;
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
    public $service;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['showService'];

    public function mount()
    {
        $this->page_title = __('admin.services');
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function showService($id)
    {
        $this->service = Service::query()->find($id);
        $this->form = $this->service->toArray();

        $this->emit("showServiceModal", $id);
    }

    public function resetData()
    {
        $this->reset(['search']);
    }
    public function getRecords()
    {
        return Service::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('dubai_fee', 'like', '%'.$this->search.'%')
                        ->orWhere('service_fee', 'like', '%'.$this->search.'%')
                        ->orWhere('amount', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate();
    }

    public function emptyForm()
    {
        $this->form =[];
    }
    public function update()
    {
        $this->validate();
        $data = Arr::except($this->form,['id', 'updated_at', 'created_at']);
        $data['amount'] = intval($this->form['dubai_fee']) + intval($this->form['service_fee']);

        Service::query()->find($this->form['id'])->update($data);

        $this->form = [];
        session()->flash('success',__('admin.edit_successfully'));

        return redirect()->to(route('admin.services'));
    }

    public function store()
    {
        $this->validate();
        $this->form['amount'] = intval($this->form['dubai_fee']) + intval($this->form['service_fee']);
        Service::query()->create($this->form);
        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.services'));
    }

    public function getRules(){
        return [
            'form.name'=>'required|max:500',
            'form.dubai_fee' => ['required'],
            'form.service_fee' => ['required'],
            'form.amount' => ['nullable'],
        ];
    }
    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.service.index', compact('records'))->layout('layouts.admin');
    }
}
