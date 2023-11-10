<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

class Login extends Component
{
    public $username, $password, $remember_me, $error_message = '';

    public function login()
    {
        $this->validate();
        if (auth('users')->attempt(['username' => $this->username, 'password' => $this->password], $this->remember_me)) {
            if (auth()->user()->is_verified == 0 && auth()->user()->user_type == 'soldier') {
                return redirect()->to(route('user.verify_register_code'));
            }
            $user = auth('api-users')->user();

            return redirect()->to(route('user.dashboard'));
        } else {
            if (auth('users')->attempt(['mobile' => $this->username, 'password' => $this->password], $this->remember_me)) {
                if (auth()->user()->is_verified == 0 && auth()->user()->user_type == 'soldier') {
                    return redirect()->to(route('user.verify_register_code'));
                }

                return redirect()->to(route('user.dashboard'));
            } else {

                if (auth('users')->attempt(['email' => $this->username, 'password' => $this->password], $this->remember_me)) {
                    if (auth()->user()->is_verified == 0 && auth()->user()->user_type == 'soldier') {
                        return redirect()->to(route('user.verify_register_code'));
                    }
                    return redirect()->to(route('user.dashboard'));
                }
                $this->error_message = __('messages.Wrong_credential');
            }

        }
    }

    public function getRules()
    {
        return [
            'password' => 'required|min:8',
            'username' => 'required|max:50'
        ];
    }

    public function render()
    {
        return view('livewire.user.login');
    }
}
