<?php

namespace App\Http\Livewire\User\Service;

use App\Models\Service;
use App\Models\SubService;
use Livewire\Component;

class Show extends Component
{
    public $service;
    public $price, $subServicesIds=[];

    public function mount(Service $service)
    {
        session()->put('service_id', $service->id);
        $this->service = $service;
        $this->price = $this->service->price;
    }

    public function updatePrice($subServiceId)
    {
        $subService = SubService::find($subServiceId);

        if ($subService) {
            if (in_array($subServiceId, $this->subServicesIds)) {
                // SubService is already selected, remove it and subtract its price
                $key = array_search($subServiceId, $this->subServicesIds);
                unset($this->subServicesIds[$key]);
                session()->put('subServices', $this->subServicesIds);
                $this->price -= $subService->price;
            } else {
                // SubService is not selected, add it and add its price
                $this->subServicesIds[] = $subServiceId;

                session()->put('subServices', $this->subServicesIds);
                $this->price += $subService->price;
            }
        }
    }

    public function render()
    {
        if($this->service->is_active == 0 ){
            return view('livewire.user.not_found')->layout('layouts.user');
        }

        return view('livewire.user.service.show')->layout('layouts.user');
    }
}
