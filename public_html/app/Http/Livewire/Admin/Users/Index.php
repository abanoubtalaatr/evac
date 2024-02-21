<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\LibraryProfit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $user_type, $status, $username, $email, $page_title;
    protected $queryString = ['status', 'email', 'email', 'username',];
    public $rowNumber = 1;
    public $currentPage = 1;
    public $perPage = 15;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        //count page = 15 in first page the counter start from 1 and then in next page
        $this->page_title = __('site.users');
    }

    public function updatedPage()
    {
        // Calculate the row number for the first record on the current page
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {
        return User::query()
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'active' ? $query->whereIsActive(1) : $query->whereIsActive(0);
            })->when(!empty($this->email), function ($query) {
                return $query->where('email', 'LIKE', '%' . $this->email . '%');
            })->when(!empty($this->username), function ($query) {
                $query->where(function ($query){
                    return $query->where('username', 'LIKE', '%' . $this->username . '%');
                });
            })->latest()->paginate();
    }

    public function resetData()
    {
        $this->reset(['email','status', 'username']);
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.users.index', compact('records'))->layout('layouts.admin');
    }
}
