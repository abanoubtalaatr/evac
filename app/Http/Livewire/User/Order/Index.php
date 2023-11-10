<?php

namespace App\Http\Livewire\User\Order;

use Livewire\Component;

class Index extends Component
{

    public function getRecords()
    {
        return auth('users')->user()->orders()->orderBy('payment_type')->latest()->paginate();
    }
    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.user.order.index', compact('records'))->layout('layouts.user');
    }
}
