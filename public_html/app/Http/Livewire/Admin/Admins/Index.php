<?php

namespace App\Http\Livewire\Admin\Admins;

use App\Models\Admin;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $is_active, $name, $email, $page_title, $status;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->page_title = __('site.admins');
    }

    public function getRecords()
    {
        return Admin::query()
            ->where('is_owner', 0)
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'active' ? $query->where('is_active', 1) : $query->where('is_active', 0);
            })
            ->when($this->name, function ($query) {
                return $query->where('name', 'LIKE', '%' . $this->name . '%');
            })
            ->when($this->email, function ($query) {
                return $query->where('email', 'LIKE', '%' . $this->email . '%');
            })
            ->latest()
            ->paginate();
    }

    public function toggleStatus(Admin $admin)
    {

        if ($admin->is_active == 1) {
            $admin->update(['is_active' => '0']);
        } else {
            $admin->is_active = '1';
            $admin->save();
        }
    }

    public function resetData()
    {
        $this->reset(['status', 'name', 'email']);
    }

    public function render()
    {
        $records = $this->getRecords();
        $records->map(function ($admin){
            foreach ($admin->roles as $role){
                if($role->is_owner ==1) {
                    $admin->owner = true;
                }
            }
        });

        return view('livewire.admin.admins.index', compact('records'))->layout('layouts.admin');
    }
}
