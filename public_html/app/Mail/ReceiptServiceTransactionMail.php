<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptServiceTransactionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $serviceTransaction;

    public function __construct($serviceTransaction)
    {
        $this->serviceTransaction = $serviceTransaction;
    }

    public function build()
    {
        return $this
            ->subject("EVAC - Service Transaction Receipt")
            ->view('emails.ServiceTransaction.receipt');
    }
}
