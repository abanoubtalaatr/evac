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

class Create extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;

    public function mount()
    {
        $this->page_title = __('site.create_oil_brands');
    }


    public function store()
    {
        $this->validate();

        OilBrand::create($this->form);

        return redirect()->to(route('admin.oil_brands'))->with('message', __('site.oil_brands_created_successfully'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:3|max:200|string|unique:oil_brands,name_ar',
            'form.name_en' => 'required|min:3|max:200|unique:oil_brands,name_en',
        ];
    }


    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.oil-brands.create')->layout('layouts.admin');
    }
}
