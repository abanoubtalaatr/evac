<?php

namespace App\Http\Livewire\Admin\Opinion;

use App\Models\Opinion;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Create extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $image;

    public function mount()
    {
        $this->page_title = __('site.create_opinions');
    }

    public function store()
    {
        $this->validate();

        $this->form['image'] =
            $this->image?
                $this->image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->image->extension(),'public') : null;

        Opinion::create($this->form);
        session()->flash('success',__('admin.create_successfully'));
        return redirect()->to(route('admin.opinions'))->with('message', __('site.opinion_created_successfully'));
    }

    public function getRules()
    {
        return [
            'form.name' => 'required|min:3',
            'form.opinion' => 'required|min:3',
            'image'=>'required|file|mimes:png,jpg,jpeg|max:10240'
        ];
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.opinion.create')->layout('layouts.admin');
    }
}
