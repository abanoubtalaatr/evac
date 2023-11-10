<?php

namespace App\Http\Livewire\Admin;

use App\Models\Admin;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;
use Spatie\Permission\Models\Role;

class Notifications extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;

    public function mount()
    {
        Notification::whereNull('when_read')->whereIsAdmin(1)->update(['when_read' => now()]);

        $this->page_title = __('site.notifications');
    }

    public function render()
    {
        $records = Notification::query()->where('is_admin', 1)->latest()->paginate();

        return view('livewire.admin.notifications', compact('records'))->layout('layouts.admin');
    }
}
