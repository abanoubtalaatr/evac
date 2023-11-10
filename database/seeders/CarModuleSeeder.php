<?php

namespace Database\Seeders;

use App\Models\CarModule;
use Illuminate\Database\Seeder;

class CarModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CarModule::query()->create( [
            'name_ar' => 'mirror',
            'name_en' => 'mirro',
            'car_brand_id' => 3
        ]);
    }
}
