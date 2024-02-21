<?php

namespace App\Http\Livewire\Admin\Slider;

use App\Models\Slider;
use Livewire\Component;

class Delete extends Component{
    public function mount(Slider $slider){
        $slider->delete();
        session()->flash('success',__('admin.delete_successfully'));
        return redirect()->to(route('admin.slider'))->with('success_message',(__('site.slider_deleted_successfully')));
    }
}
