<?php

namespace App\Http\Livewire\Admin\Slider;

use App\Models\Slider;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Validation\Validator;
use App\Http\Livewire\Traits\ValidationTrait;

class Edit extends Component{
    use WithFileUploads,ValidationTrait;

    public $form,$page_title,$picture;
    public function mount(Slider $slider){
        $this->slider = $slider;
        $this->form = Arr::except($slider->toArray(),['updated_at','created_at','id']);
        $this->page_title = __('site.edit_slider');
    }

    public function store(){
        $this->validate();
//        $this->form['picture'] =
//            $this->picture?
//                $this->picture->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->picture->extension(),'public') : $this->slider->picture;
        $this->slider->update($this->form);
        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(route('admin.slider'));
    }


    public function updatedFormPicture(){
        $this->withValidator(function (Validator $validator) {
            if($validator->errors()->has('form.picture')){
                $this->form['picture'] = '';
            }
        })->validateOnly('form.picture');
    }


    public function getRules(){
        return [
            'form.title_ar'=>'required|max:500',
            'form.title_en'=>'required|max:500',

            'form.description_ar'=>'required|max:500',
            'form.description_en'=>'required|max:500',

//            'picture'=>'nullable|file|mimes:png,jpg,jpeg|max:10240',
            'form.is_active' => 'boolean'
        ];
    }
    public function render(){
        return view('livewire.admin.slider.create')->layout('layouts.admin');
    }
}
