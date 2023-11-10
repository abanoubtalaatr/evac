<?php

namespace App\Http\Livewire\User\Car;

use Livewire\Component;

class Create extends Component
{
    public function getRecords()
    {
        return auth('users')->user()->cars()->paginate(5);
    }
    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.user.car.create', compact('records'))->layout('layouts.user');
    }
}
