<?php

namespace App\Http\Livewire\User;

use App\Models\Setting;
use Livewire\Component;

class About extends Component
{
    public function render()
    {
       $settings = Setting::query()->first();
       $about = \App\Models\Page::query()->where('title_en', 'like', "%about%")->first();

        return view('livewire.user.about',compact('settings','about'))->layout('layouts.user');
    }
}
