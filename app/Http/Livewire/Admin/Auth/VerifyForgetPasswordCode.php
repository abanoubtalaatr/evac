<?php

namespace App\Http\Livewire\Admin\Auth;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Admin;
use Livewire\Component;
use Illuminate\Support\Facades\Redis;

class VerifyForgetPasswordCode extends Component
{
    use ValidationTrait;

    public $code, $show_new_password_form, $new_password, $new_password_confirmation, $error_message;
    public $redis_code;
    public $admin;

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
            $this->admin->update(['password' => bcrypt($this->new_password)]);
            return redirect()->to(route('admin.login_form'));
        }
    }

    public function getRules()
    {
        return [
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'new_password_confirmation' => 'required|same:new_password'
        ];
    }

    public function getMessages()
    {
        return [
            'new_password.regex' => __('site.password_constrains'),
        ];
    }

    public function verifyCode()
    {
        $this->error_message = '';

        if(is_null($this->code) || empty($this->code)){
            $this->error_message = __('site.field_is_empty');
        }
        elseif ($this->code != Redis::get('reset_password_code_value.' . $this->admin->id)) {
            $this->error_message = __('site.code_is_wrong');
        } else {
            $this->show_new_password_form = 1;
            $this->error_message = '';
        }
    }

    public function render()
    {
        return view('livewire.admin.auth.verify-forget-password-code')->layout('layouts.auth');
    }
}
