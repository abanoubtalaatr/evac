<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

class Notification extends Component
{
    public $notifications;

    public function mount()
    {
        $notifications = auth('users')->user()->notifications()->where('when_read', null)->update(['when_read' => now()]);
    }
    public function getRecords()
    {
        return auth('users')->user()->notifications()->where('type', '!=', 'admin')->paginate();
    }

    public function render()
    {
        $records  = $this->getRecords();

        return view('livewire.user.notifications',compact('records'))->layout('layouts.user');;
    }
}
