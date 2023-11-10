<?php

namespace App\Http\Livewire\Admin\Service;

use App\Models\Service;
use App\Models\SubService;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Create extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $subServices;
    public $image, $video_url;

    public function mount()
    {
        $this->page_title = __('site.create_services');

        $this->subServices = SubService::query()->where('is_active',1 )->get();
    }


    public function store()
    {
        $this->validate();
        $this->form['image'] =
            $this->image?
                $this->image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->image->extension(),'public') : null;

        $this->form['video_url'] =
            $this->video_url?
                $this->video_url->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->video_url->extension(),'public') : null;

        Service::create($this->form);
        session()->flash('success',__('admin.create_successfully'));
        return redirect()->to(route('admin.services'))->with('message', __('site.services_created_successfully'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:services,name_ar',
            'form.name_en' => 'required|min:3|max:200|unique:services,name_en',
            'form.price' => 'required|integer',
            'form.time' => 'required',

            'form.description_ar' => 'required|min:3|max:1000',
            'form.description_en' => 'required|min:3|max:1000',
            'image'=>'required|file|mimes:png,jpg,jpeg|max:10240',
            'video_url'=>'nullable|mimes:mp4,mov,avi',
        ];
    }


    public function render()
    {
        return view('livewire.admin.services.create')->layout('layouts.admin');
    }
}
