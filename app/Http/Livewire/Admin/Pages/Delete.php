<?php

namespace App\Http\Livewire\Admin\Pages;

use App\Models\Page;
use Livewire\Component;

class Delete extends Component{
    public function mount(Page $page){
        $page->delete();
        return redirect()->to(route('admin.pages.index'))->with('success_message',(__('site.page_deleted_successfully')));
    }
}
