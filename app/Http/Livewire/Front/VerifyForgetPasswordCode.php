<?php

namespace App\Http\Livewire\Front;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Admin;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class VerifyForgetPasswordCode extends Component
{
    use ValidationTrait;

    public $code, $show_new_password_form, $new_password, $new_password_confirmation, $error_message;
    public $redis_code;

    public function mount(Admin $admin)
    {
        $this->admin = $admin;
        $this->show_new_password_form = 0;
        $this->redis_code = Redis::get('reset_password_code_value.' . $this->admin->id);
    }


    public function store()
    {
        $this->validate();
        if ($this->redis_code == $this->code) {
            $this->user->update(['password' => bcrypt($this->new_password)]);
            return redirect()->to(route('user.login_form'));
        }
    }

    public function getRules()
    {
        return [
            'new_password' => 'required|min:8',
            'new_password_confirmation' => 'required|same:new_password'
        ];
    }


    public function verifyCode()
    {
        if ($this->code != Redis::get('reset_password_code_value.' . $this->user->id)) {
            $this->error_message = __('site.code_is_wrong');
        } else {
            $this->show_new_password_form = 1;
            $this->error_message = '';
        }
    }


    public function render()
    {
        return view('livewire.admin.auth.verify-forget-password-code');
    }
}
