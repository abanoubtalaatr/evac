<?php

namespace App\Http\Livewire\User;

use App\Models\Contact;
use App\Models\Setting;
use Livewire\Component;
use App\Mail\ContactUsMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Livewire\Traits\ValidationTrait;

class ContactUs extends Component
{
    use ValidationTrait;

    public $form;
    public $success_message = null, $latitude, $longitude;

    public function mount()
    {
        $settings = Setting::query()->first();
        $this->latitude = $settings->lat ?? env('DEFAULT_LATITUDE');
        $this->longitude = $settings->lng?? env('DEFAULT_LONGITUDE');
        $this->form = [];
        $this->page_title = __('site.contact_us');
    }

    public function store()
    {
        $this->validate();
        Contact::create($this->form);

        Mail::send(new ContactUsMail($this->form['sender_email'], $this->form['sender_name'], $this->form['message'], $this->form['mobile']));

        $this->form = [];
        $this->success_message = __('site.your_message_was_sent');
        $this->dispatchBrowserEvent('scroll-to-top');
    }

    protected function getRules()
    {
        return [
            'form.sender_name' => 'required|max:200',
            'form.sender_email' => 'required|max:200|email:rfc,dns',
            'form.message' => 'required|max:500',
            'form.mobile' => 'required',
            'form.subject' => 'required'
        ];
    }

    public function render()
    {
        $settings = Setting::first();

        return view('livewire.user.contact',compact('settings'))->layout('layouts.user');
    }
}
