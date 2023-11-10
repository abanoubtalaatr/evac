<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutInactiveUser
{
    public function handle($request, Closure $next)
    {
        if (auth('users')->user() && auth('users')->user()->is_active != 1) {
            auth('users')->logout();
            return redirect()->to(route('user.login'))->with('error_message', __('site.your_account_has_been_deactivated'));
        }

        if (auth('api-users')->user() && auth('api-users')->user()->is_active != 1) {
            // i want to check this user is new as solider
            if(auth('api-users')->user()->is_verified == 0 && auth('api-users')->user()->user_type =='soldier') {
                return $next($request);
            }
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => __('site.your_account_has_been_deactivated')], 401);
        }
        return $next($request);
    }
}
