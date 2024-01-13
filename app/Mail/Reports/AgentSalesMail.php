<?php

namespace App\Mail\Reports;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Dompdf\Dompdf;
use Dompdf\Options;

class AgentSalesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fromDate, $toDate, $reportUrl;

    public function __construct($fromDate, $toDate, $reportUrl)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->reportUrl = $reportUrl;
    }

    public function build()
    {
        return $this->attach($this->reportUrl, [
            'as' => 'agent_sales_export.csv',
            'mime' => 'application/csv',
        ])
            ->subject("EVAC - " . "Agent sales Report")
            ->view('emails.TravelAgent.agent-applications-body');

    }

}
