<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\PaymentTransaction;
use App\Models\Service;
use App\Models\VisaType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;


class PrintController extends Controller
{
    public function printLastReceipt($agent)
    {
        $agent = Agent::query()->find($agent);

        $paymentTransaction = $agent->paymentTransactions()->latest()->first();

        return view('print-last-payment-history', compact('paymentTransaction'));
    }
    public function printDailyReport()
    {

        $title = "Daily reports";

        $dayReport['visaTypes'] = VisaType::with(['applications'])->get();

        $dayReport['services'] = Service::with(['serviceTransactions'])->get();

        return view('livewire.admin.PrintReports.daily_reports',compact('title','dayReport'));
    }
}
