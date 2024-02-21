<?php

namespace App\Http\Livewire\Admin\Order;

use App\Models\Order;
use App\Models\Service;
use App\Models\SubService;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Show extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $service;
    public $carBrands;
    public $latitude;
    public $longitude;

    public function mount(Order $order)
    {
        $this->setLocation(37.7749, -122.4194);
        $this->page_title = __('site.order_details');
        $this->order = $order;
        $this->form = Arr::except($order->toArray(), ['updated_at', 'created_at', 'id']);
    }
    public function setLocation($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        $this->emit('updateMap', $this->latitude, $this->longitude);
    }
    public function render()
    {
        return view('livewire.admin.orders.show')->layout('layouts.admin');
    }
}
