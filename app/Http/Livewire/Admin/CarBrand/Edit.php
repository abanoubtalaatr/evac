<?php

namespace App\Http\Livewire\Admin\CarBrand;

use App\Models\CarBrand;
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
    public $carBrand;

    public function mount(CarBrand $carBrand)
    {
        $this->page_title = __('site.edit_car_brands');
        $this->carBrand = $carBrand;
        $this->form = Arr::except($carBrand->toArray(), ['updated_at', 'created_at', 'id']);
    }


    public function update()
    {
        $this->validate();
        $this->carBrand->update($this->form);
        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(route('admin.car_brands'))->with('message', __('site.edit_car_brands'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:1|max:200|string|unique:car_brands,name_ar,' . $this->carBrand->id,
            'form.name_en' => 'required|min:1|max:200|unique:car_brands,name_en,' . $this->carBrand->id,
            ];
    }


    public function toggleStatus(CarBrand $carBrand)
    {
        $carBrand->update(['is_active' => !$carBrand->is_active]);
    }

    public function render()
    {

        return view('livewire.admin.car-brands.edit')->layout('layouts.admin');
    }
}
