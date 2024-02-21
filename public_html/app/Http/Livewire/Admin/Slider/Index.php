<?php

namespace App\Http\Livewire\Admin\Slider;

use App\Models\Slider;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component{
    use WithPagination;
    public $page_title;

    public function mount(){
        $this->page_title = __('general.slider');
    }


    public function render(){
        $records = Slider::paginate();
        return view('livewire.admin.slider.index',compact('records'))->layout('layouts.admin');
    }
}
