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

    public $fromDate, $toDate,$attachmentPath;

    public function __construct($fromDate, $toDate, $attachmentPath)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->attachmentPath = $attachmentPath;
    }

    public function build()
    {
        return $this->attach($this->attachmentPath, [
            'as' => 'agent_sales_report.xlsx',
            'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])
            ->subject("EVAC - " . "Agent sales Report")
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
        $html = view('livewire.admin.PrintReports.agent_sales')->with([
            'from' => $this->fromDate,
            'toDate' => $this->toDate,
            'email' => true,
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
