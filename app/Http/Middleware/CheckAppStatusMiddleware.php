<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class CheckAppStatusMiddleware
{
    public function handle($request, Closure $next)
    {
        $settings = Setting::first();
        $appStatus = $settings->app_status;
        $offlineMessage = $settings->app_inactive_message;

        if ($appStatus === 'inactive') {
//             Application is offline, respond with 503 status code and offline message
            return response()->json(['message' => $offlineMessage], 503);
        }

        // Application is online, allow the request to proceed
        return $next($request);
    }
}
