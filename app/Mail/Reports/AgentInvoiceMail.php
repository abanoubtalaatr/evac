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

        $dompdf = new Dompdf($options);
        $html = view('livewire.admin.PrintReports.agent_invoices')->with([
            'agent' => $this->agent,
            'invoice' => $invoice,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}