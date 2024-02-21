<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Application;
use App\Models\DayOffice;
use App\Models\ServiceTransaction;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
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
            ->save('report.pdf');

        $closedBy = DayOffice::query()->where('day_status', "0")->latest()->first();
        $today = Carbon::today();
        $applications_created_today = Application::whereDate('created_at', $today)->count();
        $serviceTransactionsToday = ServiceTransaction::whereDate('created_at', $today)->count();

        $html ='';
        if($closedBy){
            $admin = Admin::query()->find($closedBy->end_admin_id);

            $today = Carbon::parse(now())->format('Y-m-d');

            $html .= "<p>Day closed by ( $admin->name - $today)</p>";
            $html .="<p>{{Total visa applications today $applications_created_today}}</p>";
            $html .="<p>{{Total services today $serviceTransactionsToday }}</p>";
        }

        Mail::send('emails.TravelAgent.agent-applications-body', ['html' => $html], function ($message) use ($pdf, $request) {
            $message->to($request->email)
                ->subject('Daily Report ' . now()->format('d/m/Y'))
                ->attachData($pdf->output(), 'attachment.pdf', ['mime' => 'application/pdf']);
        });

        unlink('report.pdf');

        return response()->json(['success' => 'Email sent successfully.']);
    }

}
