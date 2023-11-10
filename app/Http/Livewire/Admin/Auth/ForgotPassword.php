<?php

namespace App\Http\Livewire\Admin\Auth;

use App\Mail\ContactEmail;
use App\Mail\OtpMail;
use App\Models\Admin;
use App\Services\OTPService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use App\Services\GenerateCodeService;

class ForgotPassword extends Component{
    public $email,$error_message;

    public function sendCode()
    {
        $this->error_message = '';
        $this->validate();

        if(!$admin_id = optional(Admin::whereEmail($this->email)->first())->id){
            $this->error_message = __('site.ensure_you_write_correct_email');
        }

        if($admin_id){
            $code = GenerateCodeService::getCode();
//            OTPService::generateCode('reset_password',$admin_id,$code);
            //send the code to with sms getway
            Mail::to($this->email)->send(new OtpMail($code));
            $admin = Admin::find($admin_id);
            $admin->update(['otp' => $code]);
            return redirect()->to(route('admin.verify_forget_password_code',$admin_id));
        }
        return 0;
    }

    public function render(){
        return view('livewire.admin.auth.forgot_password')->layout('layouts.auth');
    }

    public function getRules(){
        return [
            'email'=>'required|email'
        ];
    }
}
