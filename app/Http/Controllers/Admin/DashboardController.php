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
        $oil_brand = OilBrand::count();
        $services_count = Service::count();
        $orders_count = Order::count();
        $total_money= Order::whereStatus('finished')->sum('total');
        return view('admin.dashboard.home',compact('client_count','oil_brand','services_count', 'orders_count','total_money',));
    }
}
