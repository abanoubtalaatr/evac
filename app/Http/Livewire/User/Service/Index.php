<?php

namespace App\Http\Livewire\User\Service;

use App\Models\Service;
use Livewire\Component;

class Index extends Component
{

    public function getRecords()
    {
        return Service::query()->whereIsActive(1)->paginate();
    }
    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.user.service.index', compact('records'))->layout('layouts.user');;
    }
}
