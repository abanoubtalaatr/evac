<?php

namespace App\Http\Livewire\Admin\Oil;

use App\Models\CarBrand;
use App\Models\CarModule;
use App\Models\Oil;
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
    public $oil;
    public $carBrands;

    public function mount(Oil $oil)
    {
        $this->page_title = __('site.edit_car_modules');
        $this->oil = $oil;
        $this->oilBrands = OilBrand::query()->whereIsActive(1)->get();
        $this->form = Arr::except($oil->toArray(), ['updated_at', 'created_at', 'id']);
    }


    public function update()
    {
        $this->validate();
        $this->oil->update($this->form);
        return redirect()->to(route('admin.oils'))->with('message', __('site.edit_oils'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:oils,name_ar,'. $this->oil->id,
            'form.name_en' => 'required|min:3|max:200|unique:oils,name_en,'. $this->oil->id,
            'form.oil_brand_id' => 'required|exists:oil_brands,id',
        ];
    }

    public function render()
    {

        return view('livewire.admin.oil.edit')->layout('layouts.admin');
    }
}
