<?php

namespace App\Http\Livewire\Admin;

use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class ContactIndex extends Component{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $page_title;
    public $status,$sender_name,$sender_email;
    protected $queryString = ['status','sender_name', 'sender_email'];
    public function mount(){
        $this->page_title = __('site.contact_us');
    }

    public function updated(){
        $this->resetPage();
    }

    private function getRecords(){
        return Contact::query()
            ->when($this->status,function($query){
                return $query->whereStatus($this->status);
            })->when($this->sender_name,function($query){
                return $query->where('sender_name','LIKE','%'.$this->sender_name.'%');
            })->when($this->sender_email,function($query){
                return $query->where('sender_email','LIKE','%'.$this->sender_email.'%');
            })->paginate();
    }

    public function render(){
        return view('livewire.admin.contact-index',['records'=>$this->getRecords()]);
    }
}
