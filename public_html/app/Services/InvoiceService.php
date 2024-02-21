<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\VisaType;
use function App\Helpers\vatRate;

class InvoiceService
{
    public function recalculateVat($newAmount, $dubaiFee)
    {
        $vatRAte = $this->vatRate();

        if(!empty($newAmount)){
            if(($newAmount - $dubaiFee) > 0) {
                return ($newAmount -  $dubaiFee) * $vatRAte;
            }else{
                return 0;
            }
        }
        return 0;

    }

    public function vatRate()
    {
        $setting = Setting::query()->first();

        $value = (int) filter_var($setting->vat_rate, FILTER_SANITIZE_NUMBER_INT);

        return ($value / 100);
    }

    public function recalculateServiceFee($amount, $dubaiFee)
    {
        if(!empty($amount)){
            return $amount - $dubaiFee;
        }
        return 0;
    }
}
