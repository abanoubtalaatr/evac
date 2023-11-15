<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->create([
            'office_id' => '1',
            'office_name' => 'Evac system',
            'registration_no' => '8575959687',
            'mobile' => '+96174747474',
            'vat_no' => '753y',
            'no_of_days_to_check_visa' => '5',
        ]);
    }
}
