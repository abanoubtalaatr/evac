<?php

namespace Database\Seeders;

use App\Models\VisaProvider;
use Illuminate\Database\Seeder;

class VisaProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonFilePath = database_path('seeders/visa_providers.json');
        $jsonContent = file_get_contents($jsonFilePath);

        $dataArray = json_decode($jsonContent, true);
        foreach ($dataArray as $item){
            VisaProvider::query()->create([
                'id' => $item['providerID'],
                'name' => $item['providerName'],
            ]);
        }
    }
}
