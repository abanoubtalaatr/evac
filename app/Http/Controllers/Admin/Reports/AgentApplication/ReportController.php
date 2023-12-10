<?php

namespace App\Http\Controllers\Admin\Reports\AgentApplication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function printReport(Request $request)
    {
        return view('livewire.admin.PrintReports.agent_application');
    }

}
