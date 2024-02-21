<?php

namespace App\Http\Livewire\Admin\Contact;

use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $sender_email;
    public $sender_name;
    public $status;
    public $perPage =10;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->page_title = __('site.contact');
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {
        return Contact::query()
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'replied' ? $query->whereStatus('replied') : $query->whereStatus('unreplied');
            })->when($this->sender_name, function ($query) {
                $query->where(function ($query){
                    return $query->where('sender_name', 'LIKE', '%' . $this->sender_name . '%');
                });
            })->when($this->sender_email, function ($query) {
                $query->where(function ($query){
                    return $query->where('sender_email', 'LIKE', '%' . $this->sender_email . '%');
                });
            })
            ->latest()
            ->paginate();
    }

    public function resetData()
    {
        $this->reset(['status', 'sender_name' ,'sender_email']);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.contact.index', compact('records'))->layout('layouts.admin');
    }
}
