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

class Create extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $carBrands;

    public function mount()
    {
        $this->page_title = __('site.create_car_module');

        $this->carBrands = CarBrand::query()->where('is_active',1 )->get();
    }


    public function store()
    {
        $this->validate();

        CarModule::create($this->form);
        session()->flash('success',__('admin.create_successfully'));
        return redirect()->to(route('admin.car_modules'))->with('message', __('site.car_modules_created_successfully'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:car_modules,name_ar',
            'form.name_en' => 'required|min:3|max:200|unique:car_modules,name_en',
            'form.car_brand_id' => 'required|exists:car_brands,id',
        ];
    }


    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.car-modules.create')->layout('layouts.admin');
    }
}
