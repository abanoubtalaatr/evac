<?php

namespace App\Http\Livewire\User\Auth;

use App\Models\User;
use App\Services\GenerateCodeService;
use Livewire\Component;

class Signup extends Component
{
    public $step = 1 ;
    public $form;
    public $mobile;
    public $message;
    public $username, $email, $latitude, $longitude;

    public function mount($mobile)
    {
        $this->mobile = $mobile;
        $this->latitude = 23.8859;
        $this->longitude = 45.0792;

    }

    public function updateCoordinates($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function store()
    {
        $this->validate();
        $this->form['email'] = $this->email;
        $this->form['username'] = $this->username;
        $this->form['latitude'] = $this->latitude;
        $this->form['longitude'] = $this->longitude;

        $user = User::query()->whereMobile($this->mobile)->first();

        auth('users')->loginUsingId($user->id);
        $user->update($this->form);

        return redirect()->to(route('home'));

    }

    public function getRules()
    {
        return [
            'username' => ['required', 'string', 'min:3','max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'latitude' => ['nullable',],
            'longitude' => ['nullable'],
        ];
    }



    public function render()
    {
        return view('livewire.user.auth.sign-up')->layout('layouts.user_auth');
    }
}
