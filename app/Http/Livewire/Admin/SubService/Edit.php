<?php

namespace App\Http\Livewire\Admin\SubService;

use App\Models\Service;
use App\Models\SubService;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Edit extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $subService;
    public $services;

    public function mount(SubService $subService)
    {
        $this->page_title = __('site.edit_sub_services');
        $this->subService = $subService;
        $this->services = Service::query()->whereIsActive(1)->get();

        $this->form = Arr::except($subService->toArray(), ['updated_at', 'created_at', 'id']);
    }

    public function update()
    {
        $this->validate();
        $this->subService->update($this->form);
        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(route('admin.sub_services'))->with('message', __('site.edit_sub_servcies'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:sub_services,name_ar,' . $this->subService->id,
            'form.name_en' => 'required|min:3|max:200|unique:sub_services,name_en,' .$this->subService->id,
            'form.price' => 'required|integer|gt:0',
            'form.time' => 'required',
            'form.service_id' => 'required|exists:services,id'
            ];
    }

    public function render()
    {
        return view('livewire.admin.sub-services.edit')->layout('layouts.admin');
    }
}
