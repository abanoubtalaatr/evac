<?php

namespace App\Http\Livewire\Admin\CarBrand;

use App\Models\CarBrand;
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
    public $user;
    public $image;

    public function mount()
    {
        $this->page_title = __('site.create_car_brand');
    }

    public function store()
    {
        $this->validate();

        CarBrand::create($this->form);
        session()->flash('success',__('admin.create_successfully'));
        return redirect()->to(route('admin.car_brands'))->with('message', __('site.car_brands_created_successfully'));
    }

    public function getRules()
    {
        return [
            'form.name_ar' =>'required|min:1|max:200|string|unique:car_brands,name_ar',
            'form.name_en' => 'required|min:1|max:200|unique:car_brands,name_en',
        ];
    }


    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.car-brands.create')->layout('layouts.admin');
    }
}
