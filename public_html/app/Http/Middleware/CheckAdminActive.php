<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;


class CheckAdminActive
{
    public function handle($request, Closure $next)
    {


        if (auth('admin')->user() && auth('admin')->user()->is_active == 0) {

            $request->session()->flush(); // remove all the session data
            flash(trans('messages.warning_activate'))->success();
            Auth::logout(); // logout user

        }

        return $next($request);
    }
}
