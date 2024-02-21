<?php

namespace App\Http\Livewire\Admin\Role;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Index extends Component{
    use WithPagination;
    public $page_title, $status, $name;
    protected $paginationTheme = 'bootstrap';

    public function mount(){
        $this->page_title = __('site.roles');
    }
    public function getRecords()
    {
        return Role::query()
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'active' ? $query->whereIsActive(1) : $query->whereIsActive(0);
            })->when(!empty($this->name), function ($query) {
                $query->where(function ($query){
                    return $query->where('name_ar', 'LIKE', '%' . $this->name . '%')
                        ->orWhere('name', 'LIKE', '%' . $this->name . '%');
                });
            })->latest()->paginate();
    }

    public function resetData()
    {
        $this->reset(['name', 'status']);
    }
    public function toggleStatus(Role $role)
    {
        if ($role->is_active == 1) {
            $role->update(['is_active' => '0']);
        } else {
            $role->is_active = '1';
            $role->save();
        }
    }

    public function render(){
        $records = $this->getRecords();

        return view('livewire.admin.role.index',compact('records'))->layout('layouts.admin');
    }
}
