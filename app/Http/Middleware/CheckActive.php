<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;


class CheckActive
{
    public function handle($request, Closure $next)
    {


        if (auth('users')::user() && auth('users')::user()->is_active == 0) {

            $request->session()->flush(); // remove all the session data
            flash(trans('messages.warning_activate'))->success();
            Auth::logout(); // logout user

        }

        return $next($request);
    }
}
