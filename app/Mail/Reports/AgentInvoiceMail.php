<?php

namespace App\Mail\Reports;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Dompdf\Dompdf;
use Dompdf\Options;

class AgentInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agent, $fromDate, $toDate;

    public function __construct($agent, $fromDate, $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->agent = $agent;
    }

    public function build()
    {
        $name = $this->agent ? $this->agent->name:"";
        return $this->attachData($this->generatePdf(), 'agent_invoice.pdf')
            ->subject("EVAC - "  . $name. " Invoice")
            ->view('emails.TravelAgent.agent-applications-body');

    }

    private function generatePdf()
    {
        // Configure Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $invoice = \App\Models\AgentInvoice::query()
            ->where('agent_id', $this->agent)
            ->whereDate('from', $this->fromDate)
            ->whereDate('to', $this->toDate)
            ->first();
        $dompdf = new Dompdf($options);
        // Load HTML content
        $html = view('livewire.admin.PrintReports.agent_invoices')->with([
            'from' => $this->fromDate,
            'toDate' => $this->toDate,
            'agent' => $this->agent,
            'invoice' => $invoice->id

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
