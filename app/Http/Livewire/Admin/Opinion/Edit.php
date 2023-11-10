<?php

namespace App\Http\Livewire\Admin\Opinion;

use App\Models\Opinion;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Edit extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $opinion;
    public $image;

    public function mount(Opinion $opinion)
    {
        $this->page_title = __('site.edit_opinions');
        $this->opinion = $opinion;
        $this->form = Arr::except($opinion->toArray(), ['updated_at', 'created_at', 'id']);
        $this->form['image'] = url('uploads/pics/'. $this->opinion->image);
    }

    public function update()
    {

        $this->validate();

        $this->form['image'] =
            $this->image?
                $this->image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->image->extension(),'public') : $this->opinion->image;

        $this->opinion->update($this->form);
        session()->flash('success',__('admin.edit_successfully'));
        return redirect()->to(route('admin.opinions'))->with('message', __('site.opinions_edited_successfully'));
    }


    public function getRules()
    {
         return [
             'form.name' => 'required|min:3',
             'form.opinion' => 'required|min:3',
             'image'=>'nullable|file|mimes:png,jpg,jpeg|max:10240'
         ];
    }

    public function render()
    {
        return view('livewire.admin.opinion.edit')->layout('layouts.admin');
    }
}
