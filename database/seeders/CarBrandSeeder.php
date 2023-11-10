<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use Illuminate\Database\Seeder;

class CarBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carBrands = [
          [
              'name_ar' => 'نيسان صني',
              'name_en' => 'Nissan Sunny',
          ],
            [
                'name_ar' => 'تويوتا كورولا',
                'name_en' => 'Toyota Corolla',
            ],
            [
                'name_ar' => 'كيا سيراتو',
                'name_en' => 'Kia Cerato',
            ],
            [
                'name_ar' => 'هيونداي إلنترا',
                'name_en' => 'Hyundai Elantra',
            ],
            [
                'name_ar' => 'هيونداي أكسنت',
                'name_en' => 'Hyundai Accent ',
            ],
        ];

        foreach ($carBrands as $carBrand) {
            CarBrand::updateOrCreate([
                'name_ar' => $carBrand['name_ar'],
                'name_en' => $carBrand['name_en']
            ], [

                'name_ar' => $carBrand['name_ar'],
                'name_en' => $carBrand['name_en']
            ]);
        }

    }
}
