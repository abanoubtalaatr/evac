<?php

namespace App\Http\Livewire\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;
use Spatie\Permission\Models\Role;

class Profile extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $admin;
    public $roles;
    public $image;
    public $selectedRoles = [];


    public function mount()
    {
        $this->page_title = __('site.profile');

        $this->admin = Admin::query()->find(auth('admin')->id());
        $this->form = Arr::except($this->admin->toArray(), ['updated_at', 'created_at', 'id']);

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


        $this->admin->update(Arr::except($this->form, ['password_confirmation']));

        return redirect()->to(route('admin.profile'))->with('message', __('site.admin_edited_successfully'));
    }


    public function getRules()
    {
        return [
            'form.email' => 'required|email:dns,rfc|unique:admins,email,' . $this->admin->id,
            'form.name' => 'required|min:3|max:100|unique:admins,name,' . $this->admin->id,
            'form.phone' => 'nullable|integer|digits:9|bail|unique:admins,phone,' . $this->admin->id,
            'form.password' => 'nullable|min:8',
            'form.password_confirmation' => 'nullable|same:form.password',
            'image'=>'nullable|file|mimes:png,jpg,jpeg|max:10240'
        ];
    }


    public function toggleStatus(Admin $admin)
    {
        $admin->update(['is_active' => !$admin->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.profile')->layout('layouts.admin');
    }
}
