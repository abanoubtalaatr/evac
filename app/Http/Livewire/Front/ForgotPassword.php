<?php

namespace App\Http\Livewire\Front;

use App\Services\OTPService;
use App\Models\User;
use Livewire\Component;
use App\Services\GenerateCodeService;

class ForgotPassword extends Component{
    public $auth_field,$error_message;
    public function sendCode(){

        if(!$user_id = optional(User::whereUsername($this->auth_field)->first())->id){
            if(!$user_id = optional(User::whereMobile($this->auth_field)->first())->id){
                if(!$user_id = optional(User::whereEmail($this->auth_field)->first())->id){
                    $this->error_message = __('general.invalidLoginData');
                }
            }
        }

        if($user_id){
            $code = GenerateCodeService::getCode();
            OTPService::generateCode('reset_password',$user_id,$code);
            return redirect()->to(route('user.verify_forget_password_code',$user_id));
        }

    }

    public function render(){
        return view('livewire.front.forgot-password');
    }

    public function getRules(){
        return [
            'auth_field'=>'required'
        ];
    }
}
