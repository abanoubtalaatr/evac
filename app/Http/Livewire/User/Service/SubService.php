<?php

namespace App\Http\Livewire\User\Service;

use Livewire\Component;

class SubService extends Component
{
    public $form;

    public function mount()
    {
        $this->getSubServices();
    }

    public function getSubServices()
    {
        $this->subServices = \App\Models\SubService::query()->where('service_id', session()->get('service_id'))->get();
    }

    public function changeSubService($subServiceId)
    {
        $this->emit('chooseSubService', $subServiceId);
    }

    public function render()
    {
        return view('livewire.user.service.sub-service');
    }
}
