<?php

namespace App\Http\Livewire\Traits\Admin\Application;

use App\Models\Application;
use App\Models\BlackListPassport;
use App\Models\Setting;

trait ApplicationChecks
{
    public function checkPassportInBlackList(): bool
    {
        $blackList = BlackListPassport::query()->where('passport_number', $this->form['passport_no'])->first();

        if($blackList) {
            return true;
        }
        return false;
    }
    public function checkExpiryPassport(): bool
    {
        $expiryDateTime = new \DateTime($this->form['expiry_date']);
        $currentDateTime = now();
    
        // Calculate the signed difference in days
        $difference = $expiryDateTime->getTimestamp() - $currentDateTime->getTimestamp();
        $daysDifference = (int) floor($difference / 86400); // Convert seconds to days
    
        // Default threshold
        $numberOfExpireDays = 180;
    
        // Check settings for a custom threshold
        $settings = Setting::query()->first();
        if ($settings && $settings->no_of_days_to_check_visa) {
            $numberOfExpireDays = $settings->no_of_days_to_check_visa;
        }
    
    
        // Check if expiry date is in the past or within the threshold
        if ($daysDifference < $numberOfExpireDays) {
            return true;
        }
    
        return false;
    }
    
    
    

    public function checkPassportHasMoreThanOneApplication(): bool
    {
        $settings = Setting::query()->first();
        $numberOfDaysToCheckVisa = 90;
        if ($settings) {
            $numberOfDaysToCheckVisa = $settings->no_of_days_to_check_visa;
        }
        $this->numberOfDaysToCheckVisa = $numberOfDaysToCheckVisa;

        $previousApplications = Application::where('passport_no', $this->form['passport_no'])
            ->where(function ($query) use ($numberOfDaysToCheckVisa) {
                $query->where('created_at', '>', now()->subDays($numberOfDaysToCheckVisa))
                    ->orWhereDate('created_at', now()->format('Y-m-d'));
            })
            ->get();

        if ($previousApplications->count() >= 1) {
            $this->passportApplications = $previousApplications;
            return true;
        }
        return false;
    }

}
