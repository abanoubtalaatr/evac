<?php

namespace App\Http\Livewire\User\Car;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModule;
use Livewire\Component;

class StoreCar extends Component
{
    use ValidationTrait;
    public $car, $carBrands, $carModules, $form,$cars, $message;
    public $colors = ['white', 'black', 'red', 'green', 'yellow', 'blue', 'green'];

    public function mount()
    {
        $this->carBrands = CarBrand::query()->whereIsActive(1)->get();
        $this->carModules = CarModule::query()->whereIsActive(1)->get();
        $this->form['user_id'] = auth('users')->id();
    }

    public function store()
    {
        $this->validate();
        $newCar = Car::query()->create($this->form);
        $this->message = __('site.created_successfully');
        $this->resetData();
        $this->emit('carAdded', $newCar);
    }

    public function resetData()
    {
        $this->form['car_brand_id'] = null;
        $this->form['car_module_id'] = null;
        $this->form['plat_number'] = null;
        $this->form['plat_char']  = null;
        $this->form['fuel_type'] = null;
        $this->form['color'] = null;
        $this->form['manufacturing_year'] = null;
        $this->form['number_of_engine_valves'] = null;
        $this->message = null;
    }

    public function getRules()
    {
        return [
            'form.car_brand_id'=> "required|exists:car_brands,id",
            'form.car_module_id' => ['required', 'exists:car_modules,id'],
            'form.plat_number' => ['required', 'max:4'],
            'form.plat_char' => ['required', 'string'],
            'form.fuel_type' => ['required', 'string'],
            'form.color' => ['required','string'],
            'form.manufacturing_year' => ['required', 'integer'],
            'form.number_of_engine_valves' => ['required', 'integer'],
        ];
    }

    public function render()
    {
        return view('livewire.user.car.store-car');
    }
}
