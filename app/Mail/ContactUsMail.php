<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $phone;
    public $email;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $name, $message, $phone)
    {
        $this->email = $email;
        $this->name = $name;
        $this->message = $message;
        $this->phone = $phone;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to(env('CLIENT_EMAIL', 'abanoubtalaat@fudex.com.sa'));
        $this->replyTo($this->email, $this->name);
        $this->subject(sprintf("New contact message from %s", $this->name));

        return $this->markdown('emails.contact_us');
    }
}
