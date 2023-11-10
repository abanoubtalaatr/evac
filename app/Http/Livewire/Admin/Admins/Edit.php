<?php

namespace App\Http\Livewire\Admin\Admins;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $admin;
    public $roles;
    public $selectedRoles = [];
    public $image;
    public function mount(Admin $admin)
    {
        $this->page_title = __('site.edit_admin');
        $this->admin = $admin;
        $this->roles = Role::where('is_owner', 0)->get();
        $this->form = Arr::except($admin->toArray(), ['updated_at', 'created_at', 'id']);

        $this->form['roles'] = DB::table('model_has_roles')->where('model_id', $this->admin->id)->pluck('role_id')->toArray();
        $this->form['image'] = url('uploads/pics/'. $this->admin->image);
    }

    public function update()
    {

        $this->validate();

        if (isset($this->form['password'])) {
            $this->form['password'] = bcrypt($this->form['password']);
        }
        $this->form['image'] =
            $this->image?
                $this->image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->image->extension(),'public') : $this->admin->image;

        if (!empty($this->form['roles'])) {
            $this->admin->syncRoles($this->form['roles']);
        }

        session()->flash('success',__('admin.edit_successfully'));
        $this->admin->update(Arr::except($this->form, ['roles','password_confirmation']));
        return redirect()->to(route('admin.admins.index'))->with('message', __('site.admin_edited_successfully'));
    }


    public function getRules()
    {
        return [
            'form.email' => 'required|email:dns,rfc|unique:admins,email,' . $this->admin->id,
            'form.name' => 'required|min:3|max:100|unique:admins,name,' . $this->admin->id,
            'form.phone' => 'nullable|integer|digits:9|bail|unique:admins,phone,' . $this->admin->id,
            'form.password' => [
                'nullable',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'form.password_confirmation' => 'nullable|same:form.password',
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

    public function toggleStatus(Admin $admin)
    {
        $admin->update(['is_active' => !$admin->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.admins.edit')->layout('layouts.admin');
    }
}
