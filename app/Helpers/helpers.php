<?php

namespace App\Helpers;

use App\Models\Opinion;

if (!function_exists('sendSms')) {
    function sendSms($mobile, $message, $mediaUrl)
    {
    }
}

if (!function_exists('generateCode')) {
    function generateCode()
    {
        return (config('app.env')!='local')? mt_rand(1000,9999) : 1234;
    }
}

if (!function_exists('rejectOrderNotificationStoreInDatabase')) {
    function rejectOrderNotificationStoreInDatabase($userId, $orderId, $reason)
    {
        $title_ar = config('appMessages.notifications.order.reject.title_ar');
        $content_ar = config('appMessages.notifications.order.reject.content_ar') . ' ' . $reason;

        $title_en = config('appMessages.notifications.order.reject.title_en');
        $content_en = config('appMessages.notifications.order.reject.content_en') . ' ' . $reason;

        \App\Models\Notification::query()->create([
            'title_ar' => $title_ar,
            'content_ar' => $content_ar,
            'title_en' => $title_en,
            'content_en' => $content_en,
            'type' => 'reject',
            'user_id' => $userId,
            'subject_id' => $orderId,
        ]);
    }
}

if (!function_exists('createDatabaseNotification')) {
    function createDatabaseNotification($userId, $orderId, $title_ar, $content_ar, $title_en, $content_en, $type, $isAdmin)
    {
        \App\Models\Notification::query()->create([
            'title_ar' => $title_ar,
            'content_ar' => $content_ar,
            'title_en' => $title_en,
            'content_en' => $content_en,
            'type' => $type,
            'user_id' => $userId,
            'subject_id' => $orderId,
            'is_admin' => $isAdmin
        ]);
    }
}



if (!function_exists('send_sms')) {
    function send_sms($numbers, $msg)
    {
        $data = [
            "userName" => env('MESGAT_USER_NAME'),
            "password" => env('MESGAT_PASSWORD'),
            "userSender" => env('MESGAT_SENDER_NAME'),
            "numbers" => $numbers,
            "apiKey" => env('MESGAT_KEY'),
            "msg" => $msg,
            "msgEncoding" => "UTF8",
        ];
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'https://www.msegat.com/gw/sendsms.php', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Accept-Language' => app()->getLocale() == 'ar' ? 'ar-Sa' : 'en-Uk',
            ],
            'body' => json_encode($data),
        ]);

        if ($res) {
            $data = json_decode($res->getBody()->getContents());
            info(print_r($data, true));
            if (isset($data->code)) {
                //----- if code == 1 => Success, otherwise failed.
                $code = $data->code;
                $message = $data->message;
                return $code == 1 ? 1 : $code;
                // return response()->json(['code'=> $code,'message' => __($message)]);
            }
            return 0;
        }
        return 0;
    }
}


if (!function_exists('userRateServiceBefore')) {
    function userRateServiceBefore($user, $order)
    {
        return Opinion::query()->where('user_id', $user)->where('order_id', $order)->exists();
    }
}


