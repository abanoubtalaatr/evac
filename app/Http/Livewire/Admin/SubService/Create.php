<?php

namespace App\Http\Livewire\Admin\SubService;

use App\Models\Service;
use App\Models\SubService;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Create extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $services;

    public function mount()
    {
        $this->page_title = __('site.create_sub_services');
        $this->services = Service::query()->whereIsActive(1)->get();
    }


    public function store()
    {
        $this->validate();

        SubService::create($this->form);
        session()->flash('success',__('admin.create_successfully'));
        return redirect()->to(route('admin.sub_services'))->with('message', __('site.sub_services_created_successfully'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:sub_services,name_ar',
            'form.name_en' => 'required|min:3|max:200|unique:sub_services,name_en',
            'form.price' => 'required|integer|gt:0',
            'form.time' => 'required',
            'form.service_id' => 'required|exists:services,id',
        ];
    }

    public function toggleStatus(SubService $subService)
    {
        $subService->update(['is_active' => !$subService->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.sub-services.create')->layout('layouts.admin');
    }
}
