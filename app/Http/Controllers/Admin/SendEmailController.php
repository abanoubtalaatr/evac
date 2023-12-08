<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function send(Request $request)
    {
        if (!$request->email) {
            return response()->json(['error' => 'Recipient email not provided.'], 400);
        }

        if (!$request->className) {
            return response()->json(['error' => 'Class name not provided.'], 400);
        }

        $reportClass = app()->make($request->className);

        $html = $reportClass->printReport();
        $pdf = Facade\Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setWarnings(false)
            ->save('myfile.pdf');

        // Send email with PDF attachment
        Mail::send([], [], function ($message) use ($pdf, $request) {
            $message->to($request->email)
                ->subject('Subject of the Email')
                ->attach('myfile.pdf', ['as' => 'attachment.pdf', 'mime' => 'application/pdf']);
        });

        // Remove the temporary PDF file
        unlink('myfile.pdf');

        return response()->json(['success' => 'Email sent successfully.']);
    }

}
