<?php

namespace App\Http\Livewire\Admin\Role;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form, $page_title, $permissions;

    public function mount()
    {
        $this->page_title = __('site.create_role');
        $this->permissions = Permission::all();
    }

    public function store()
    {
        $this->validate();
        $this->form['guard_name'] = 'admin';
        $role = Role::create(Arr::except($this->form, 'permissions'));

        foreach ($this->form['permissions'] as $permission) {

            DB::table('role_has_permissions')->insert([
                'role_id' => $role->id,
                'permission_id' => $permission
            ]);
        }
        session()->flash('success',__('admin.create_successfully'));
        return redirect()->to(url('admin/role/index'));
    }


    public function getRules()
    {
        return [
            'form.name_ar' => 'required|max:300|unique:roles,name_ar',
            'form.name' => 'required|max:300|unique:roles,name',
            'form.permissions' => 'required|array',
        ];
    }

    public function render()
    {
        return view('livewire.admin.role.create')->layout('layouts.admin');
    }
}
