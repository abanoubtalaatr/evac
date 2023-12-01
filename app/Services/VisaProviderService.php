<?php

namespace App\Services;

use App\Models\Application;
use Carbon\Carbon;

class VisaProviderService
{
    public function totalNumberOfVisas($visaProviderId)
    {
        return Application::query()->where('visa_provider_id', $visaProviderId)->count();
    }

    public function totalNumberOfVisasInCurrentMonth($visaProviderId)
    {
        $currentMonth = Carbon::now()->month;

        return Application::query()
            ->where('visa_provider_id', $visaProviderId)
            ->whereMonth('created_at', $currentMonth)
            ->count();
    }

}
