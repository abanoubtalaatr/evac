<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function send(Request $request)
    {
        if (!$request->email) {
            return response()->json(['error' => 'Recipient email not provided.'], 400);
        }

        $pdfPath = redirect()->to('en/admin/print-daily-report')->getContent();
        $pdf = PDF::loadHTML($pdfPath);

        $recipientEmail = $request->email;
        $data = [];

        Mail::send([], $data, function ($message) use ($pdf, $recipientEmail) {
            $message->to($recipientEmail)
                ->subject('Subject of the Email')
                ->attachData($pdf->output(), 'attachment.pdf', ['mime' => 'application/pdf']);
        });
        return response()->json(['success' => 'Email sent successfully.']);
    }
}
