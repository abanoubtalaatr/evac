<?php

namespace App\Http\Livewire\Admin\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $page_title;

    public function mount(){
        $this->page_title = __('site.pages');
    }


    public function render(){
        $records = Page::paginate();
        return view('livewire.admin.page.index',compact('records'))->layout('layouts.admin');
    }
}
