<?php

namespace App\Http\Livewire\User\Car;

use App\Models\Car;
use Livewire\Component;

class Delete extends Component
{
    public function mount(Car $car)
    {
       $car->delete();
       return redirect()->to(route('user.cars'));
    }
}
