<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Create extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $user;
    public $image;
    public $message;

    public function mount()
    {
        $this->page_title = __('site.create_user');
    }


    public function store()
    {
        $this->form['is_active'] = 1;
        $this->form['is_verified']  =1;
        $this->validate();
        if (isset($this->form['password'])) {
            $this->form['password'] = bcrypt($this->form['password']);
        }
        $this->form['avatar'] =
            $this->image?
                $this->image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->image->extension(),'public') : ''    ;

        User::create(Arr::except($this->form, ['password_confirmation']));
//        $this->message = __('site.created_successfully');
//        sleep(1);
        $this->dispatchBrowserEvent('showToast', ['type' => 'success', 'message' => 'Data saved successfully']);
        session()->flash('success',__('admin.create_successfully'));
        return redirect()->to(route('admin.users.index'))->with('message', __('site.user_created_successfully'));
    }

    public function getRules()
    {
        return [
            'form.email' =>'required|max:200|email:dns,rfc|unique:users,email',
            'form.username' => 'required|min:3|max:100|unique:users,username',
            'form.mobile' =>'required|integer|digits:9|bail|unique:users,mobile',
            'form.password' =>[
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'form.password_confirmation' => 'nullable|same:form.password',
            'image'=>'nullable|file|mimes:png,jpg,jpeg|max:10240'
        ];
    }


    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
    }

    public function getMessages()
    {
        return [
            'form.password.regex' => __('site.password_constrains'),
        ];
    }

    public function render()
    {

        return view('livewire.admin.users.create')->layout('layouts.admin');
    }
}
