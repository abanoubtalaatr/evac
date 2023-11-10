<?php

namespace App\Http\Livewire\User\Auth;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $user, $username, $email, $mobile, $message, $form, $latitude, $longitude;
    public $photo,$avatar;

    public function mount()
    {
        $this->user = auth('users')->user();
        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->mobile = $this->user->mobile;
        $this->latitude = $this->user->latitude;
        $this->longitude = $this->user->longitude;
    }

    public function update()
    {
        $this->validate();
        $this->form['username'] = $this->username;
        $this->form['email'] = $this->email;
        $this->form['mobile'] = $this->mobile;
        $this->form['latitude'] = $this->latitude;
        $this->form['longitude'] = $this->longitude;

        if ($this->photo) {
            $this->form['avatar'] = $this->photo->storeAs(date('Y/m/d'), Str::random(50).'.'.$this->photo->extension(), 'public');
        }

        $this->user->update($this->form);
        $this->message = __('site.profile_update_successfully');
    }

    public function uploadPhoto()
    {

    }

    public function updateCoordinates($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getRules()
    {
        return [
            'username'  => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email,'. $this->user->id],
            'mobile' => ['required', 'unique:users,mobile,'. $this->user->id],
        ];
    }

    public function render()
    {
        return view('livewire.user.auth.profile')->layout('layouts.user');
    }
}
