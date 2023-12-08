<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Spatie\Browsershot\Browsershot;

class SendEmailController extends Controller
{
    public function send(Request $request)
    {
        if (!$request->email) {
            return response()->json(['error' => 'Recipient email not provided.'], 400);
        }

        $url = route('admin.print.daily_reports'); // Replace with your actual URL
        $pdfPath = storage_path('app/attachment.pdf');
        $recipientEmail = $request->email;
        $data = [];

        Browsershot::url($url)
            ->save($pdfPath);

        Mail::send([], $data, function ($message) use ($pdfPath, $recipientEmail) {
            $message->to($recipientEmail)
                ->subject('Subject of the Email')
                ->attach($pdfPath, ['as' => 'attachment.pdf', 'mime' => 'application/pdf']);
        });

        return response()->json(['success' => 'Email sent successfully.']);
    }
}
