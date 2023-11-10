<?php

namespace App\Http\Livewire\User\Auth;

use App\Models\User;
use App\Services\GenerateCodeService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Symfony\Contracts\Translation\TranslatorTrait;

class Login extends Component
{
    public $step = 1 ;
    public $form;
    public $mobile;
    public $message;
    public $number1='', $number2='', $number3='', $number4='';
    use TranslatorTrait;
    public function attempt()
    {
        $this->validate();
        //check if this number is exist or not
        $user = User::query()->whereMobile($this->mobile)->first();

        if($user) {
            $this->sendCode();

            return redirect()->to(route('user.verify_code', ['mobile' => $this->mobile]));
        }else{
            //create a user with this mobile and

            User::query()->create(['mobile' => $this->mobile, 'password' => Hash::make('password')]);
            $this->sendCode();

            return redirect()->to(route('user.verify_code', ['mobile' => $this->mobile]));
        }
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
          'mobile' => ['required', 'starts_with:5', 'min:9', 'max:9'],
        ];
    }

    protected function getMessages()
    {
        return [
          'mobile.starts_with' => __('site.mobile_must_start_with_five'),
            'mobile.min' => __('site.mobile_number_must_be_nine_number')
        ];
    }

    public function render()
    {
        return view('livewire.user.auth.login')->layout('layouts.user_auth');;
    }
}
