<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Edit extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $user;
    public $image;

    public function mount(User $user)
    {
        $this->page_title = __('site.edit_user');
        $this->user = $user;
        $this->form = Arr::except($user->toArray(), ['updated_at', 'created_at', 'id']);
        $this->form['image'] = url('uploads/pics/'. $this->user->avatar);

    }


    public function update()
    {
        $this->validate();
        if (isset($this->form['password'])) {
            $this->form['password'] = bcrypt($this->form['password']);
        }

        $this->form['avatar'] =
            $this->image?
                $this->image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->image->extension(),'public') : $this->user->avatar;


        $this->user->update(Arr::except($this->form, ['password_confirmation','image']));
        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(route('admin.users.index'))->with('message', __('site.user_edited_successfully'));
    }

    public function getRules()
    {
        return [
            'form.email' =>'required|max:200|email:dns,rfc|unique:users,email,'. $this->user->id,
            'form.username' => 'required|min:3|max:100|unique:users,username,'. $this->user->id,
            'form.mobile' =>'required|integer|digits:9|bail|unique:users,mobile,'. $this->user->id,
            'form.password' =>[
                'nullable',
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

    public function render()
    {

        return view('livewire.admin.users.edit')->layout('layouts.admin');
    }
}
