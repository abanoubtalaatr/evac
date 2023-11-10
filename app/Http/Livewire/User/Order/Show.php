<?php

namespace App\Http\Livewire\User\Order;

use App\Models\Order;
use Livewire\Component;

class Show extends Component
{
    public $order;

    public function mount(Order $order)
    {
        $this->order = $order;
    }


    public function render()
    {
        if($this->order->user_id != auth('users')->id() ){
            return view('livewire.user.not_found')->layout('layouts.user');
        }

        return view('livewire.user.order.show')->layout('layouts.user');
    }
}
