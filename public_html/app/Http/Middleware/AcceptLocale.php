<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AcceptLocale
{

    public function handle(Request $request, Closure $next){
        $locale = ($request->hasHeader('lang')) ? $request->header('lang') : "ar";
        $locale = ($request->hasHeader('Accept-Language')) ? $request->header('Accept-Language') : "ar";
        app()->setLocale($locale);
        return $next($request);
    }
}
