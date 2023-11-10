<?php

namespace App\Http\Livewire\User\Car;

use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModule;
use Livewire\Component;

class Edit extends Component
{
    public $car, $carBrands, $carModules;
    public $colors = ['white', 'black', 'red', 'green', 'yellow', 'blue', 'green'];
    public $plat_number, $plat_char, $car_brand_id, $car_module_id, $fuel_type, $color, $manufacturing_year, $number_of_engine_valves;
    public $form;

    public function mount(Car $car)
    {
        $this->car = $car;
        $this->carBrands = CarBrand::query()->whereIsActive(1)->get();
        $this->carModules = CarModule::query()->whereIsActive(1)->get();
        $this->plat_char = $car->plat_char;
        $this->plat_number = $car->plat_number;
        $this->form = $car->toArray();
    }

    public function update()
    {
        $this->validate();
        $this->car->update($this->form);
        $this->message = __('site.updated_successfully');

    }

    public function getRules()
    {
        return [
            'form.car_brand_id'=> ['required', 'exists:car_brands,id'],
            'form.car_module_id' => ['required', 'exists:car_modules,id'],
            'form.plat_number' => ['required', 'min:4'],
            'form.plat_char' => ['required', 'min:4'],
            'form.fuel_type' => ['required', 'string'],
            'form.color' => ['required','string'],
            'form.manufacturing_year' => ['required', 'integer'],
            'form.number_of_engine_valves' => ['required', 'integer'],
        ];
    }

    public function getRecords()
    {
        return auth('users')->user()->cars()->paginate(5);
    }
    public function render()
    {
        $records = $this->getRecords();

        if($this->car->user_id != auth('users')->user()->id){
            return view('livewire.user.not_found')->layout('layouts.user');
        }
        return view('livewire.user.car.edit', compact('records'))->layout('layouts.user');
    }
}
