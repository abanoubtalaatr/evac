<?php

namespace App\Http\Controllers\Admin\Reports\DailyReport;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\VisaType;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function printReport()
    {
        $title = "Daily reports";

        $dayReport['visaTypes'] = VisaType::with(['applications'])->get();

        $dayReport['services'] = Service::with(['serviceTransactions'])->get();

        return view('livewire.admin.PrintReports.daily_reports',compact('title','dayReport'));
    }
}
