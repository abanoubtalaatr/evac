<?php

namespace App\Http\Livewire\Admin\Admins;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $admin;
    public $roles;
    public $image;

    public function mount(Admin $admin)
    {

        $this->page_title = __('site.create_admin');
        $this->admin = $admin;
        $this->roles = Role::where('is_owner', 0)->get();

        $this->form = Arr::except($admin->toArray(), ['updated_at', 'created_at', 'id']);
    }


    public function store()
    {
        $this->validate();

        if (isset($this->form['password'])) {
            $this->form['password'] = bcrypt($this->form['password']);
        }
        $this->form['image'] =
            $this->image?
                $this->image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->image->extension(),'public') : $this->admin->image;

        $admin = Admin::create(Arr::except($this->form, ['roles', 'password_confirmation']));

        foreach ($this->form['roles'] as $role) {
            DB::table('model_has_roles')->insert(['role_id' => $role, 'model_id' => $admin->id, 'model_type' => 'App\Models\Admin']);
        }

        session()->flash('success',__('admin.create_successfully'));
        return redirect()->to(route('admin.admins.index'))->with('message', __('site.admin_created_successfully'));
    }

    public function getRules()
    {
        return [
            'form.email' => 'required|email|unique:admins,email',
            'form.name' => 'required|min:3|max:100|unique:admins,name',
            'form.phone' => 'nullable|integer|digits:9|bail|unique:admins,phone',
            'form.password' =>[
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'form.password_confirmation' => 'required|same:form.password',
            'form.roles' => 'required|array',
            'image'=>'nullable|file|mimes:png,jpg,jpeg|max:10240'
        ];
    }

    public function getMessages()
    {
        return [
            'form.password.regex' => __('site.password_constrains'),
        ];
    }


    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.admins.create')->layout('layouts.admin');
    }
}
