<?php

namespace App\Http\Livewire\Admin\Oil;

use App\Models\Oil;
use App\Models\OilBrand;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Create extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $oilBrands;

    public function mount()
    {
        $this->page_title = __('site.create_oils');

        $this->oilBrands = OilBrand::query()->active()->get();
    }


    public function store()
    {
        $this->validate();

        Oil::create($this->form);

        return redirect()->to(route('admin.oils'))->with('message', __('site.oils_created_successfully'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:oils,name_ar',
            'form.name_en' => 'required|min:3|max:200|unique:oils,name_en',
            'form.oil_brand_id' => 'required|exists:oil_brands,id',
        ];
    }

    public function render()
    {
        return view('livewire.admin.oil.create')->layout('layouts.admin');
    }
}
