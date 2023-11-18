<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class PrintApplicationController extends Controller
{
    public function show(Application $application)
    {
        return view('print-application-invoice', compact('application'));
    }
}
