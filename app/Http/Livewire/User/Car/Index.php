<?php

namespace App\Http\Livewire\User\Car;

use Livewire\Component;

class Index extends Component
{

    public function getRecords()
    {
        return auth('users')->user()->cars()->latest()->paginate(5);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.user.car.index', compact('records'))->layout('layouts.user');;
    }
}
