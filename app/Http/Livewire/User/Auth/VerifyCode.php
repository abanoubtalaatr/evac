<?php

namespace App\Http\Livewire\User\Auth;

use App\Models\User;
use App\Services\GenerateCodeService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyCode extends Component
{
    public $step = 2;
    public $form;
    public $mobile;
    public $message;
    public $number1='', $number2='', $number3='', $number4='';

    public function mount($mobile)
    {
       $this->mobile = $mobile;
    }

    public function verifyCode()
    {
        $user = User::query()->whereMobile($this->mobile)->first();

        $code = $this->number1 . $this->number2 . $this->number3. $this->number4;

        if ($user->verify_code_expiration < now()){
            $this->message = __('site.your_code_is_expire');
            return 0;
        }

        if($user->verified_code == $code){
            if(!$user->email && !$user->latitude && !$user->longitude){
                $user->update(['verified_code' => null]);

               return redirect()->to(route('user.signup', ['mobile' => $this->mobile]));
            }else{
                $user->update(['verified_code' => null, 'verify_code_expiration' => null]);

                auth('users')->loginUsingId($user->id);
                return redirect()->to(route('home'));
            }
        }else{
            $this->message = __('site.incorrect_login_code');
            return 0;
        }
    }

    public function resendCode()
    {
        $this->sendCode();
        return redirect()->back();
    }

    public function sendCode()
    {
        $user = User::query()->whereMobile($this->mobile)->first();

        $code = GenerateCodeService::getCode();
        // send code to sms
        $user->update(['verified_code' => $code, 'verify_code_expiration' => Carbon::now()->addSeconds(env('EXPIRE_TIME_FOR_SEND_CODE')??120)]);
    }

    public function getRules()
    {
        return [
          'mobile' => ['required', 'regex:/^5\d{8}$/'],
        ];
    }



    public function render()
    {
        return view('livewire.user.auth.verify')->layout('layouts.user_auth');
    }
}
