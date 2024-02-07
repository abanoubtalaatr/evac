<?php

namespace Database\Seeders;

use App\Models\BlackListPassport;
use Illuminate\Database\Seeder;

class BlackListPassportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonFilePath = database_path('seeders/blacklisted_passports.json');
        $jsonContent = file_get_contents($jsonFilePath);

        $dataArray = json_decode($jsonContent, true);
        foreach ($dataArray as $item){
            BlackListPassport::query()->create([
                'id' => $item['blackListID'],
                'office_id' => $item['officeID'],
                'passport_number' => $item['passportNumber'],
                'first_name' => $item['firstName'],
                'last_name' => $item['lastName'],
                'date_expiry' => $item['date_expiry'],
                'black_reason' => $item['reason'],
                'created_at' => $item['date_created'],
                'updated_at' => $item['date_modified'],
            ]);
        }
    }
}
