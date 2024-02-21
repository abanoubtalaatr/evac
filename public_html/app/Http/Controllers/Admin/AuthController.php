<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller{
    public function showRegisterForm(){
        return view('admin.auth.register');
    }

    public function showLoginForm(){
        return  view('admin.auth.login');
    }

    public function logout(){
        auth('admin')->logout();
        return redirect()->to(route('admin.login_form'));
    }
}
