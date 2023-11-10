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

class Create extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $carBrands;
    public $carModules;

    public function mount()
    {
        $this->page_title = __('site.create_car_cylinder');

        $this->carBrands = CarBrand::query()->where('is_active',1 )->get();
        $this->carModules = CarModule::query()->where('is_active',1 )->get();

    }
    public function store()
    {
        $this->validate();

        CarCylinder::create($this->form);
        session()->flash('success',__('admin.create_successfully'));
        return redirect()->to(route('admin.car_cylinders'))->with('message', __('site.car_modules_created_successfully'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:car_modules,name_ar',
            'form.name_en' => 'required|min:3|max:200|unique:car_modules,name_en',
            'form.car_brand_id' => 'required|exists:car_brands,id',
            'form.car_module_id' => 'required|exists:car_modules,id',
            'form.engine_type' => [
                'required',
                Rule::in(['petrol', 'diesel'])
            ],
            'form.number_of_kilos_of_oil' => 'required|integer',
        ];
    }


    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.car-cylinders.create')->layout('layouts.admin');
    }
}
