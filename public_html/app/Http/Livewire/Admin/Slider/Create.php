<?php

namespace App\Http\Livewire\Admin\Slider;

use App\Models\Slider;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Validation\Validator;
use App\Http\Livewire\Traits\ValidationTrait;

class Create extends Component{
    use WithFileUploads,ValidationTrait;

    public $form,$page_title,$picture;
    public $is_active = true;
    public function mount(){
        $this->page_title = __('site.create_slider');
    }

    public function store(){

        $this->validate();
//        $this->form['picture'] = $this->picture->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->picture->extension(),'public');

        Slider::create($this->form);
        session()->flash('success',__('admin.create_successfully'));

        return redirect()->to(route('admin.slider'));
    }


    public function updatedFormPicture(){
        $this->withValidator(function (Validator $validator) {
            if($validator->errors()->has('form.picture')){
                $this->form['picture'] = '';
            }
        })->validateOnly('form.picture');
    }

    public function updatedIsActive($value)
    {
        $this->isActive = $value ? 1 : 0;
    }



    public function getRules(){
        return [
            'form.title_ar'=>'required|max:500',
            'form.title_en'=>'required|max:500',

            'form.description_ar'=>'required|max:500',
            'form.description_en'=>'required|max:500',

//            'picture'=>'required|file|mimes:png,jpg,jpeg|max:10240',
            'form.is_active' => 'boolean'
        ];
    }
    public function render(){
        return view('livewire.admin.slider.create')->layout('layouts.admin');
    }
}
