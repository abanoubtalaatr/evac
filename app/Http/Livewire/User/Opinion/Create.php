<?php

namespace App\Http\Livewire\User\Opinion;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Opinion;
use App\Models\Order;
use Livewire\Component;
use function App\Helpers\userRateServiceBefore;

class Create extends Component
{
    use ValidationTrait;
    public $message, $rateBefore, $order;

    public $form;

    public function mount(Order $order)
    {
        $this->order = $order;
        if(userRateServiceBefore(auth()->id(), $this->order->id)){
            $this->rateBefore = true;
            $this->message = __('site.you_rate_the_service_before');
        }
    }

    public function getRules()
    {
        return [
            'form.opinion' => ['required', 'string', 'min:3', 'max:1000']
        ];
    }

    public function store()
    {
        $this->validate();


            Opinion::query()->create(
                [
                    'opinion' => $this->form['opinion'],
                    'image' => auth()->user()->avatar,
                    'is_active' => 0,
                    'name' => auth()->user()->username,
                    'order_id' => $this->order->id,
                    'user_id' => $this->order->user->id
                ]
            );

            $this->rateBefore = true;
            $this->message = __('site.rate_successfully');
            $this->form['opinion'] = '';
    }

    public function render()
    {
        return view('livewire.user.opinion.create')->layout('layouts.user');;
    }
}
