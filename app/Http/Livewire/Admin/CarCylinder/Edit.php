<?php

namespace App\Http\Livewire\Admin\CarCylinder;

use App\Models\CarBrand;
use App\Models\CarCylinder;
use App\Models\CarModule;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Edit extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $carCylinder;
    public $carBrands;
    public $carModules;

    public function mount(CarCylinder $carCylinder)
    {
        $this->page_title = __('site.edit_car_cylinder');
        $this->carCylinder = $carCylinder;
        $this->carBrands = CarBrand::query()->whereIsActive(1)->get();
        $this->carModules = CarModule::query()->whereIsActive(1)->get();
        $this->form = Arr::except($carCylinder->toArray(), ['updated_at', 'created_at', 'id']);
    }

    public function update()
    {
        $this->validate();
        $this->carCylinder->update($this->form);
        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(route('admin.car_cylinders'))->with('message', __('site.edit_car_cylinders'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:car_modules,name_ar,' .$this->carCylinder->id,
            'form.name_en' => 'required|min:3|max:200|unique:car_modules,name_en,' . $this->carCylinder->id,
            'form.car_brand_id' => 'required|exists:car_brands,id',
            'form.car_module_id' => 'required|exists:car_modules,id',
            'form.engine_type' => [
                'required',
                Rule::in(['petrol', 'diesel'])
            ],
            'form.number_of_kilos_of_oil' => 'required|integer',
            ];
    }

    public function render()
    {
        return view('livewire.admin.car-cylinders.edit')->layout('layouts.admin');
    }
}
