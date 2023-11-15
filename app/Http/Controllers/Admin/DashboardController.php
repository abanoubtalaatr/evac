<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OilBrand;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;

class DashboardController extends Controller{
    public function index(){
        $client_count = User::count();
        return view('admin.dashboard.home',compact('client_count'));
    }
}
