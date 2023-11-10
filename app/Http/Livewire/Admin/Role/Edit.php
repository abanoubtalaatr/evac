<?php

namespace App\Http\Livewire\Admin\Role;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Validation\Validator;
use App\Http\Livewire\Traits\ValidationTrait;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form, $page_title, $permissions, $selectedPermissions = [];

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->permissions = Permission::all();
        $this->selectedPermissions = $role->permissions()->pluck('id')->toArray();
        $this->form = Arr::except($role->toArray(), ['updated_at', 'created_at', 'id']);
        $this->form['permissions'] = $this->selectedPermissions;
        $this->page_title = __('site.edit_role');
    }

    public function store()
    {
        $this->validate();

        $this->role->update(Arr::except($this->form, 'permissions'));
        $this->role->syncPermissions($this->form['permissions']);

        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(url('admin/role/index'));
    }


    public function getRules()
    {
        return [
            'form.name_ar' => 'required|max:300|unique:roles,name_ar,' . $this->role->id,
            'form.name' => 'required|max:300|unique:roles,name,'.$this->role->id,
            'form.permissions' => 'required|array',
        ];
    }

    public function render()
    {
        return view('livewire.admin.role.edit')->layout('layouts.admin');
    }
}
