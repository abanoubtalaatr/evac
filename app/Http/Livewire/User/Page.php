<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

class Page extends Component
{
    public $page;
    public function mount($page)
    {
        $this->page = \App\Models\Page::query()->where('title_en', 'like', "%$page%")->first();
    }

    public function render()
    {
        $data = [];
        if($this->page){
            $data['title'] = app()->getLocale() =="ar" ? $this->page->title_ar : $this->page->title_en;
            $data['content'] =  app()->getLocale() =='ar' ? $this->page->content_ar : $this->page->content_en;
            $data['picture'] = $this->page->picture;
        }else{
            return view('livewire.user.not_found')->layout('layouts.user');
        }

        return view('livewire.user.page',compact('data'))->layout('layouts.user');;
    }
}
