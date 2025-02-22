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
        $name = $this->agent ? $this->agent->name . '_INVOICE.pdf' : 'agent_INVOICE.pdf';
        return $this->attachData($this->generatePdf(), $name, [
            'mime' => 'application/pdf',
        ])
            ->subject("EVAC - " . $name . " INVOICE")
            ->view('emails.TravelAgent.agent-applications-body', [
                'agentInvoice' => true
            ]);
    }

    private function generatePdf()
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);

        $invoice = \App\Models\AgentInvoice::query()
            ->where('agent_id', $this->agent->id)
            ->whereDate('from', $this->fromDate)
            ->whereDate('to', $this->toDate)
            ->first();

        // Mock header data (replace with actual data from your app)
        $headerData = [
            'company_name' => 'EVAC Company',
            'address' => '123 Business St, City, Country',
            'telephone' => '+1 234 567 890',
        ];

        $dompdf = new Dompdf($options);
        $html = view('livewire.admin.PrintReports.agent_invoices')->with([
            'agent' => $this->agent, // Pass full agent object
            'invoice' => $invoice,   // Pass full invoice object
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'headerData' => $headerData, // Pass header data
            'showInvoiceTitle' => true,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Debug: Save PDF locally
        // file_put_contents('test.pdf', $dompdf->output());

        return $dompdf->output();
    }
}