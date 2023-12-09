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

    public $data, $agent, $form, $toDate;

    public function __construct($data, $agent, $from, $to)
    {
        $this->data = $data;
        $this->agent = $agent;
        $this->from = $from;
        $this->toDate = $to;
    }

    public function build()
    {
        return $this->attachData($this->generatePdf(), 'application_records.pdf')
            ->subject("EVAC - " . $this->agent->name . ' - ' . "Application Report")
            ->view('livewire.admin.PrintReports.agent_application');

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
            'from' => $this->from,
            'to' => $this->toDate
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
