<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OilBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oilBrands = [
            [
              'name_ar' => 'زيت محرك فالفولين',
              'name_en' => 'Valvoline engine oil',
            ],
            [
                'name_ar' => 'زيت المحرك بينزويل',
                'name_en' => 'Pennzoil engine oil',
            ],
            [
                'name_ar' => 'زيت المحرك الكلي Total',
                'name_en' => 'Total engine oil',
            ],
            [
                'name_ar' => 'زيت موتور موبيل 1',
                'name_en' => 'Mobil 1 motor oil',
            ],
            [
                'name_ar' => 'زيت محرك كاسترول',
                'name_en' => 'Castrol engine oil',
            ],
            [
                'name_ar' => ' زيت محرك كويكر ستيت',
                'name_en' => 'Quaker State Motor Oil',
            ],
        ];
        foreach ($oilBrands as $oilBrand) {
            \App\Models\OilBrand::updateOrCreate(
                [
                    'name_ar' => $oilBrand['name_ar'],
                    'name_en' =>$oilBrand['name_en'],
                ],
                [
                    'name_ar' =>  $oilBrand['name_ar'],
                    'name_en' => $oilBrand['name_en'],
                ]
            );
        }
    }
}
