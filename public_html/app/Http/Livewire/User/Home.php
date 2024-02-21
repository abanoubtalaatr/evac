<?php

namespace App\Http\Livewire\User;

use App\Models\Setting;
use Livewire\Component;

class Home extends Component
{
    public $record;

    public function mount()
    {
        $this->record = Setting::query()->first();
    }
    public function render()
    {
        return view('livewire.user.welcome')->layout('layouts.user');;
    }
}
