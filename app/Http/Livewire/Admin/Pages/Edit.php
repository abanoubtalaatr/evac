<?php

namespace App\Http\Livewire\Admin\Pages;

use App\Models\Page;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Validation\Validator;
use App\Http\Livewire\Traits\ValidationTrait;

class Edit extends Component{
    use WithFileUploads,ValidationTrait;

    public $form,$page_title,$picture;
    public function mount(Page $page){
        $this->page = $page;
        $this->form = Arr::except($page->toArray(),['updated_at','created_at','id']);
        $this->page_title = __('site.edit_page');
    }

    public function store(){
        $this->validate();
        $this->form['picture'] =
            $this->picture?
                $this->picture->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->picture->extension(),'public') : $this->page->picture;
        $this->page->update($this->form);
        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(route('admin.pages.index'));
    }


    public function updatedPicture(){
        $this->withValidator(function (Validator $validator) {
            if($validator->errors()->has('picture')){
                $this->picture= '';
            }
        })->validateOnly('picture');
    }


    public function getRules(){
        return [
            'form.title_ar'=>'required|max:500',
            'form.title_en'=>'required|max:500',

            'form.desc_ar'=>'required|max:500',
            'form.desc_en'=>'required|max:500',

            'form.content_ar'=>'required|max:5000',
            'form.content_en'=>'required|max:5000',

            'form.type'=>'nullable|in:navbar,services,how_it_works,benifits,footer',
            'picture'=>'nullable|file|mimes:png,jpg,jpeg|max:10240'
        ];
    }
    public function render(){
        return view('livewire.admin.page.create')->layout('layouts.admin');
    }
}
