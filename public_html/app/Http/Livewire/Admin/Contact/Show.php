<?php

namespace App\Http\Livewire\Admin\Contact;

use App\Mail\ContactEmail;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\ValidationTrait;

class Show extends Component
{
    use WithFileUploads, ValidationTrait;

    public $form;
    public $contact;

    public function mount(Contact $contact)
    {
        $this->page_title = __('site.reply');
        $this->contact = $contact;
        $this->form = Arr::except($contact->toArray(), ['updated_at', 'created_at', 'id']);
    }

    public function update()
    {
        $this->validate();
        $this->form['status'] = 'replied';
        $this->form['admin_id'] = auth('admin')->id();

        Mail::to($this->contact->sender_email)->send(new ContactEmail($this->form['reply']));

        $this->contact->update($this->form);
        return redirect()->to(route('admin.contacts'))->with('message', __('site.contacts_created_successfully'));

    }

    public function getRules()
    {
        return [
            'form.reply'=>'required|min:3|max:2000',
        ];
    }

    public function render()
    {
        $record = $this->contact;
        return view('livewire.admin.contact.show', compact('record'))->layout('layouts.admin');
    }
}
