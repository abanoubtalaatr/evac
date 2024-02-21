<?php

namespace App\Http\Livewire\Admin\Application;

use Livewire\Component;

class Receipt extends Component
{
    public $form;

    public function mount()
    {
        $this->page_title = __('admin.receipt');
    }

    public function getRules()
    {
        return [
          'form' => '',
        ];
    }

    public function render()
    {
        return view('livewire.admin.application.receipt')->layout('layouts.admin');
    }
}
