<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class OTPService
{

    public static function generateCode(
        $code_name,
        $user_id,
        $code,
        $period_in_seconds = 720,
        $code_expires_in_seconds = 720,
        $max_times_per_period = 30
    )
    {
        $times_key = $code_name . '_code_times.' . $user_id;
        $otp_key = $code_name . '_code_value.' . $user_id;

        if (!Redis::exists($times_key)) {
            $value = 1;
            Redis::set($times_key, 1, 'EX', $period_in_seconds);
        } else {
            $value = intval(Redis::get($times_key)) + 1;
            if ($value > $max_times_per_period) {
                return response()->json([
                    'message' => 'You have exceeded allowed trials',
                    'wait_for' => Redis::ttl($times_key),
                    'redis_times' => Redis::get($times_key),
                    'code_times' => $value
                ], 400);
            }

//
            if($code_name != 'reset_password') {
                if (Redis::exists($otp_key) && Redis::ttl($otp_key) != -2) {
                    return response()->json([
                        'message' => 'You will be able to resend code after',
                        'wait_for' => Redis::ttl($otp_key),
                        'redis_times' => Redis::get($times_key),
                        'code_times' => $value
                    ], 400);
                }
            }

            Redis::set($times_key, $value, 'EX', $period_in_seconds);
        }


        Redis::set($otp_key, $code, 'EX', $code_expires_in_seconds);
        info('the redis ' . Redis::get($otp_key));
        info('expire at ' . Redis::ttl($otp_key));
        return response()->json([
            'message' => 'Code sent successfully',
            'redis_times' => Redis::get($times_key),
            'wait_for' => $code_expires_in_seconds,
            'code_times' => $value,
            // 'code'=>$code,
            'user_id' => $user_id
        ]);
    }


}
