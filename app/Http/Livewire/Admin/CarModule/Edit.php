<?php

namespace App\Http\Livewire\Admin\CarModule;

use App\Models\CarBrand;
use App\Models\CarModule;
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
    public $carModule;
    public $carBrands;

    public function mount(CarModule $carModule)
    {
        $this->page_title = __('site.edit_car_modules');
        $this->carModule = $carModule;
        $this->carBrands = CarBrand::query()->whereIsActive(1)->get();
        $this->form = Arr::except($carModule->toArray(), ['updated_at', 'created_at', 'id']);
    }


    public function update()
    {
        $this->validate();
        $this->carModule->update($this->form);
        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(route('admin.car_modules'))->with('message', __('site.edit_car_modules'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:car_modules,name_ar,' . $this->carModule->id,
            'form.name_en' => 'required|min:3|max:200|unique:car_modules,name_en,' . $this->carModule->id,
            'form.car_brand_id' => 'required|exists:car_brands,id'
            ];
    }


    public function toggleStatus(CarBrand $carBrand)
    {
        $carBrand->update(['is_active' => !$carBrand->is_active]);
    }

    public function render()
    {

        return view('livewire.admin.car-modules.edit')->layout('layouts.admin');
    }
}
