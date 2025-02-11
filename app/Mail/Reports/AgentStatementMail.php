<?php

namespace App\Mail\Reports;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentStatementMail extends Mailable
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
        $agent = Agent::query()->find($this->agent);
        $name = $agent ? $agent->name .'.pdf' : 'agent_statement.pdf';
        $agentReport = $agent ? $agent->name .' Report': 'Agent Statement Report';
        return $this->attachData($this->generatePdf(), $name)
            ->subject("EVAC" .' - ' . $agentReport)
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
        $html = view('livewire.admin.PrintReports.agent_statement')->with([
            'from' => $this->fromDate,
            'toDate' => $this->toDate,
            'agent' => $this->agent,
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
