<?php

namespace App\Http\Livewire\User;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Setting;
use App\Models\User;
use App\Services\GenerateCodeService;
use App\Services\OTPService;
use GuzzleHttp\Client;
use Livewire\Component;
use function App\Helpers\sendMessageToWhatsApp;
use function App\Helpers\sendSms;

class Register extends Component
{
    use ValidationTrait;

    public $form = [];
    public $step = 2;

    public function mount()
    {
        $this->form = ['user_type' => 'advertiser', 'default_language' => app()->getLocale()];
    }

    public function store()
    {
        $this->validate();
        unset($this->form['password_confirmation'], $this->form['terms_accepted']);
        $user = User::create(array_merge($this->form, ['password' => bcrypt($this->form['password'])]));

        if ($user->user_type == 'soldier') {
            session()->put('username', $user->username);
            $code = GenerateCodeService::getCode();
            $user->update(['verified_code' => $code]);

            sendSms($user->mobile, $code);

            return redirect()->to(route('user.verify_register_code'));
        }

        return redirect()->to(route('user.dashboard'));
    }

    public function changeUserType($new_type)
    {
        $this->form['user_type'] = $new_type;
        $this->step = 1;
    }

    public function goBack()
    {
        $this->step = 1;
    }

    public function getRules()
    {
        return [
            'form.email' => 'required_if:form.user_type,advertiser|max:200|email:dns,rfc|unique:users,email',
            'form.username' => 'required|max:100|unique:users,username',
            'form.mobile' => 'required_if:form.user_type,soldier|starts_with:5|integer|digits:9',
            'form.password' => 'required|min:8',
            'form.password_confirmation' => 'required|same:form.password',
            'form.user_type' => 'required|in:soldier,advertiser'
        ];
    }

    public function getMessages()
    {
        return [
            'form.email.required_if' => __('site.email_is_required'),
            'form.mobile.required_if' => __('site.mobile_is_required'),
            'form.mobile.starts_with' => __('site.mobile_must_start_with_five')
        ];
    }


    public function render()
    {
        return view('livewire.user.register');
    }
}
