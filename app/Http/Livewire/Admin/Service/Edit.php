<?php

namespace App\Http\Livewire\Admin\Service;

use App\Models\Service;
use App\Models\SubService;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Edit extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $service;
    public $carBrands;
    public $image, $video_url;
    public function mount(Service $service)
    {
        $this->page_title = __('site.edit_services');
        $this->service = $service;
        $this->subServices = SubService::query()->whereIsActive(1)->get();
        $this->form = Arr::except($service->toArray(), ['updated_at', 'created_at', 'id']);
    }


    public function update()
    {
        $this->validate();

        $this->form['image'] =
            $this->image?
                $this->image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->image->extension(),'public') : $this->service->image;

        $this->form['video_url'] =
            $this->video_url?
                $this->video_url->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->video_url->extension(),'public') : $this->service->video_url;

        $this->service->update($this->form);
        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(route('admin.services'))->with('message', __('site.edit_services'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:services,name_ar,' . $this->service->id,
            'form.name_en' => 'required|min:3|max:200|unique:services,name_en,' . $this->service->id,
            'form.price' => 'required|integer',
            'form.time' => 'required',
            'form.description_ar' => 'required|min:3|max:1000',
            'form.description_en' => 'required|min:3|max:1000',
            'image'=>'nullable|file|mimes:png,jpg,jpeg|max:10240',
            'video_url'=>'nullable|mimes:mp4,mov,avi',
        ];
    }


    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
    }

    public function render()
    {

        return view('livewire.admin.services.edit')->layout('layouts.admin');
    }
}
