<?php

namespace Database\Seeders;

use App\Models\VisaType;
use Illuminate\Database\Seeder;

class VisaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonFilePath = database_path('seeders/visa_types.json');
        $jsonContent = file_get_contents($jsonFilePath);

        $dataArray = json_decode($jsonContent, true);
        foreach ($dataArray as $item){
            VisaType::query()->create([
                'id' => $item['visaID'],
                'name' => $item['visaName'],
                'dubai_fee' => $item['dubaiPriceInUSD'],
                'service_fee' => $item['directPriceInUSD'],
                'total' => $item['dubaiPriceInUSD'] + $item['directPriceInUSD']
            ]);
        }

    }
}
