<?php

namespace App\Helpers;

use App\Models\Application;
use App\Models\DayOffice;
use App\Models\Opinion;
use App\Models\Setting;
use App\Models\VisaType;
use Carbon\Carbon;

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


if (!function_exists('checkDayStart')) {
    function checkDayStart($officeId)
    {
        return DayOffice::where('admin_id', auth('admin')->id())
            ->where('office_id', $officeId)
            ->where('day_start', Carbon::today())
            ->where('day_status', "1")
            ->where('day_status', '!=', "0")
            ->exists();
    }
}

if (!function_exists('checkDayRestart')) {
    function checkDayRestart($officeId)
    {
        return DayOffice::where('admin_id', auth('admin')->id())
            ->where('office_id', $officeId)
            ->where('day_start', Carbon::today())
            ->where('day_status', "2")
            ->exists();
    }
}

if (!function_exists('checkDayClosed')) {
    function checkDayClosed($officeId)
    {
        return DayOffice::where('admin_id', auth('admin')->id())
            ->where('office_id', $officeId)
            ->where('day_start', Carbon::today())
            ->where('day_status', "0")

            ->exists();
    }
}

if (!function_exists('currentDayForOffice')) {
    function currentDayForOffice($officeId)
    {
        return DayOffice::where('admin_id', auth('admin')->id())
            ->where('office_id', $officeId)
            ->where('day_start', Carbon::today())
            ->first();
    }
}

if (!function_exists('displayTextInNavbarForOfficeTime')) {
    function displayTextInNavbarForOfficeTime($officeId)
    {
        $officeDay = currentDayForOffice($officeId);
        $data = [];

        if($officeDay){
            if($officeDay->day_status == "0") {
                $data['prefix'] = "Day closed by";
                $data['day'] = $officeDay->day_start;
                $data['time'] = $officeDay->end_time;

                return $data;
            }

            if($officeDay->day_status == "1") {
                $data['prefix'] = "Day opened by";
                $data['day'] = $officeDay->day_start;
                $data['time'] = $officeDay->start_time;

                return $data;
            }
            if($officeDay->day_status == "2") {
                $data['prefix'] = "Day reopened by";
                $data['day'] = $officeDay->day_start;
                $data['time'] = $officeDay->restart_at;

                return $data;
            }
        }

        $data['prefix'] = "";
        $data['day'] = "";
        $data['time'] = "";
        return $data;
    }
}

if (!function_exists('convertNumberToWorldsInUsd')) {
    function convertNumberToWorldsInUsd($number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'One', '2' => 'Two',
            '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
            '7' => 'Seven', '8' => 'Eight', '9' => 'Nine'
        );
        $words2 = array('0' => '', '1' => 'Ten', '2' => 'Twenty',
            '3' => 'Thirty', '4' => 'Forty', '5' => 'Fifty', '6' => 'Sixty',
            '7' => 'Seventy', '8' => 'Eighty', '9' => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Million', 'Billion', 'Trillion');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 20) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred :
                    $words2[floor($number / 10)] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else
                $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($decimal) ?
            "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . " Cents" : '';
        return $result . "USD" . $points;
    }
}


if (!function_exists('vatRate')) {
    function vatRate($visaTypeId)
    {
        $setting = Setting::query()->first();

        $value = (int) filter_var($setting->vat_rate, FILTER_SANITIZE_NUMBER_INT);

        $visaType = VisaType::query()->find($visaTypeId);

        return ($value / 100 * $visaType->service_fee);
    }
}

if (!function_exists('canCloseDay')) {
    function canCloseDay($officeId)
    {
        $newApplications = Application::query()->where('status', 'new')->count();
        if($newApplications > 0) {
            return false;
        }
        return  true;
    }
}
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return '$ ' . number_format($amount, 2);
    }
}

if (!function_exists('recalculateVat')) {
    function recalculateVat($visaTypeId,$oldAmount,$newAmount, $oldVat)
    {
        $vatRAte = vatRate($visaTypeId);
        $visaType = VisaType::query()->find($visaTypeId);
        if($oldAmount != $newAmount) {
            if(($newAmount - $visaType->dubai_fee) > 0) {
              return $newAmount -  $visaType->dubai_fee * $vatRAte;
            }else{
                return 0;
            }
        }
        return $oldVat;
    }
}


