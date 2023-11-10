<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oils = [
          [
              'name_ar' => 'موبيل ',
              'name_en' => 'Mobil',
              'oil_brand_id' => 6,
          ],
            [
                'name_ar' => 'كاسترول ',
                'name_en' => 'كاسترول',
                'oil_brand_id' => 7,
            ],
        ];

        foreach ($oils as $oil) {
            \App\Models\Oil::updateOrCreate(
                [
                    'name_ar' => $oil['name_ar'],
                    'name_en' =>$oil['name_en'],
                    'oil_brand_id' => $oil['oil_brand_id']
                ],
                [
                    'name_ar' =>  $oil['name_ar'],
                    'name_en' => $oil['name_en'],
                    'oil_brand_id' => $oil['oil_brand_id']
                ]
            );
        }
    }
}
