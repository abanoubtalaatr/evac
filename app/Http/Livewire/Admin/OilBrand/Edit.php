<?php

namespace App\Http\Livewire\Admin\OilBrand;

use App\Models\CarBrand;
use App\Models\OilBrand;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Edit extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $oilBrand;

    public function mount(OilBrand $oilBrand)
    {
        $this->page_title = __('site.edit_oil_brands');
        $this->oilBrand = $oilBrand;
        $this->form = Arr::except($oilBrand->toArray(), ['updated_at', 'created_at', 'id']);
    }


    public function update()
    {
        $this->validate();
        $this->oilBrand->update($this->form);
        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(route('admin.oil_brands'))->with('message', __('site.edit_oil_brands'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:car_brands,name_ar,' . $this->oilBrand->id,
            'form.name_en' => 'required|min:3|max:200|unique:car_brands,name_en,' . $this->oilBrand->id,
            ];
    }


    public function toggleStatus(CarBrand $carBrand)
    {
        $carBrand->update(['is_active' => !$carBrand->is_active]);
    }

    public function render()
    {

        return view('livewire.admin.oil-brands.edit')->layout('layouts.admin');
    }
}
