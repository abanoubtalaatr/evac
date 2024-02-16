<?php

namespace Database\Seeders;

use App\Models\Applicant;
use Illuminate\Database\Seeder;

class ApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ini_set('memory_limit', '134217728M');

        $jsonFilePath = database_path('seeders/applicants.json');
        $jsonContent = file_get_contents($jsonFilePath);

        $dataArray = json_decode($jsonContent, true);

        foreach ($dataArray as $item){
            $expiryDate = $item['expiryDate'];
            $comparisonDate = "2021-12-31";

            $expiryDateTime = new \DateTime($expiryDate);
            $comparisonDateTime = new \DateTime($comparisonDate);

            if($item['officeID'] == '1' && $expiryDateTime >= $comparisonDateTime){
                Applicant::query()->create([
                    'id' => $item['applicantID'],
                    'name' => $item['firstName'],
                    'surname' => $item['lastName'],
                    'passport_no' => $item['passportNumber'],
                    'passport_expiry' => $item['expiryDate'],
                    'created_at' => $item['date_inserted'],
                    'updated_at' => $item['date_modified'],
                ]);
            }
        }
    }
}
