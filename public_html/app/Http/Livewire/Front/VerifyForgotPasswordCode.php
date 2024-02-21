<?php

namespace App\Http\Livewire\Front;

use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;

class VerifyForgotPasswordCode extends Component
{
    public $code, $error_message = '';

    public function verifyCode(){

        if($this->code != Redis::get('reset_password_code_value.'.$this->user->id)){
            $this->error_message = __('site.code_is_wrong');
        }else{
            $this->show_new_password_form = 1;
            $this->error_message = '';
        }
    }

    public function verify()
    {
        $this->validate();
        if (session()->has('user_mobile_to_verify')) {
            // check the code is exist in database or not if not return message this code not correct
            $user = User::whereMobile(session()->get('user_mobile_to_verify'))->first();
            if ($user) {
                // check this code from user is equal this code ,
                if ($user->verify_code == $this->code) {
                    return redirect()->to(route('user.dashboard'));
                } else {
                    $this->error_message = __('messages.verification_code_is_wrong');

                }
            }
        } else {
            return redirect()->to(route('user.dashboard'));
        }
    }

    public function getRules()
    {
        return [
            'verify_code' => 'required:max:4',
        ];
    }

    public function render()
    {
//        dd('am here');
//        dd('fdslkjfdlksj');
        return view('livewire.front.verify-account');
    }
}
