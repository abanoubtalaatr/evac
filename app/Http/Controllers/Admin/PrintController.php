<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function printLastReceipt($agent)
    {
        $agent = Agent::query()->find($agent);

        $paymentTransaction = $agent->paymentTransactions()->latest()->first();

        return view('print-last-payment-history', compact('paymentTransaction'));
    }
}
