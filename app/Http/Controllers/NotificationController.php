<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller{
    public function userNotification(Request $request){
        $data['records'] = auth('users')->user()->notifications()->paginate();
        $data['page_title'] = __('site.notifications');
        return view('user_notifications',$data);
    }
}
