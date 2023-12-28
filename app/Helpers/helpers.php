<?php

namespace App\Helpers;

use App\Models\Admin;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\DayOffice;

use App\Models\Setting;
use App\Models\VisaType;
use Carbon\Carbon;

if (!function_exists('checkDayStart')) {
    function checkDayStart($officeId)
    {
        return DayOffice::where('office_id', $officeId)
            ->where('day_start', Carbon::today())
            ->where('day_status', '!=', "0")
            ->exists();
    }
}

if (!function_exists('checkDayRestart')) {
    function checkDayRestart($officeId)
    {
        return DayOffice::where('office_id', $officeId)
            ->where('day_start', Carbon::today())
            ->where('day_status', "2")
            ->exists();
    }
}

if (!function_exists('checkDayClosed')) {
    function checkDayClosed($officeId)
    {
        return DayOffice::where('office_id', $officeId)
            ->where('day_start', Carbon::today())
            ->where('day_status', "0")

            ->exists();
    }
}

if (!function_exists('currentDayForOffice')) {
    function currentDayForOffice($officeId)
    {
        return DayOffice::where('office_id', $officeId)
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
            $data['user'] = Admin::find($officeDay['admin_id'])->name;

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
        $data['user']= '';
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

            // Check if the index is within bounds before accessing the arrays
            if (isset($digits[$i]) && isset($words[$number]) && isset($words2[floor($number / 10)])) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 20)
                    ? $words[$number] . " " . $digits[$i] . $plural . " " . $hundred
                    : $words2[floor($number / 10)] . " " . $words[$number % 10] . " " . $digits[$i] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($decimal) ?
            "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . " Cents" : '';
        return $result . "USD only" . $points;
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
        $newApplications = Application::query()->withoutGlobalScope('visibleApplications')->where('status', 'new')->count();
        if($newApplications > 0) {
            return false;
        }
        return  true;
    }
}
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return  number_format($amount, 2);
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


if (!function_exists('vatRatePercentage')) {
    function vatRatePercentage($visaTypeId)
    {
        $setting = Setting::query()->first();

        $value = (int) filter_var($setting->vat_rate, FILTER_SANITIZE_NUMBER_INT);

        $visaType = VisaType::query()->find($visaTypeId);

        return ($value / 100 * $visaType->service_fee);
    }
}

if (!function_exists('recalculateVatInServiceTransaction')) {
    function recalculateVatInServiceTransaction($serviceFee, $newAmount, $dubaiFee, $oldVat)
    {
        $setting = Setting::query()->first();

        $value = (int) filter_var($setting->vat_rate, FILTER_SANITIZE_NUMBER_INT);
        $vatRate = $value / 100;


        if((intval($newAmount) - $dubaiFee) > 0) {
            return ($newAmount -  $dubaiFee) * $vatRate;
        }else{
            return 0;
        }

        return $oldVat;
    }
}


if (!function_exists('vatForServiceFee')) {
    function vatForServiceFee($serviceFee)
    {
        $setting = Setting::query()->first();

        $value = (int) filter_var($setting->vat_rate, FILTER_SANITIZE_NUMBER_INT);

        return ($value / 100 * $serviceFee);
    }
}

if (!function_exists('isOwner')) {
    function isOwner()
    {
       return  auth('admin')->user()->is_owner;
    }
}

