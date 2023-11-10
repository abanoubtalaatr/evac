<?php

namespace App\Http\Livewire\User\Auth;

use App\Models\User;
use App\Services\GenerateCodeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Logout extends Component
{
    public function mount()
    {
        auth('users')->logout();
        return redirect()->to(route('home'));
    }
}
