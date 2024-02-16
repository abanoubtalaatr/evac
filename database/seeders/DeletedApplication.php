<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DeletedApplication extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonFilePath = database_path('seeders/deleted_applications.json');
        $jsonContent = file_get_contents($jsonFilePath);

        $dataArray = json_decode($jsonContent, true);

        foreach ($dataArray as $item){
            $expiryDate = $item['deletionDate'];
            $comparisonDate = "2021-12-31";

            $expiryDateTime = new \DateTime($expiryDate);
            $comparisonDateTime = new \DateTime($comparisonDate);

            if($expiryDateTime >= $comparisonDateTime){
                \App\Models\DeletedApplication::query()->create([
                    'id' => $item['deleteID'],
                    'agent_id' =>$item['applicationID'],
                    'passport_no' => '',
                    'reference_no' => $item['reference'],
                    'applicant_name' => $item['applicantName'],
                    'deletion_date' => $item['deletionDate'],
                    'user_name' =>$item['userName'],
                    'office_name' => '',
                    'delete_reason' => $item['deleteReason'],
                    'last_app_status' => $item['lastAppStatus'],
                ]);
            }
        }
    }
}
