<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Dompdf\Dompdf;
use Dompdf\Options;

class AgentApplicationsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data, $agent, $fromDate, $toDate;

    public function __construct($data, $agent, $fromDate, $toDate)
    {
        $this->data = $data;
        $this->agent = $agent;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function build()
    {
        $name = $this->agent? $this->agent->name :"";
        return $this->attachData($this->generatePdf(), $name?$name.'.pdf' :  'application_records.pdf')
            ->subject("EVAC - "   .$name. " - Application Report")
            ->view('emails.TravelAgent.agent-applications-body');
    }

    private function generatePdf()
    {
        // Configure Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        // Load HTML content
        $html = view('livewire.admin.PrintReports.agent_application')->with([
            'data' => $this->data,
            'agent' => $this->agent,
            'from' => $this->fromDate,
            'toDate' => $this->toDate
        ])->render();

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // Set paper size
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF (first pass to get total pages)
        $dompdf->render();

        // Output the generated PDF (second pass to generate final PDF with correct page count)
        $output = $dompdf->output();

        return $output;
    }
}
