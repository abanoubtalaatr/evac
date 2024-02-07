<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\VisaType;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '134217728M');

        $jsonFilePath = database_path('seeders/applications.json');
        $jsonContent = file_get_contents($jsonFilePath);

        $dataArray = json_decode($jsonContent, true);

        foreach ($dataArray as $item){
            $expiryDate = now()->format('Y-m-d');
            $status = $item['status'];
            $amount= 0;
            $serviceFee =0;
            $dubaiFee = 0;

            $applicant = Applicant::query()->find($item['applicantID']);
            $agent = Agent::query()->find($item['agentID']);
            $visa = VisaType::query()->find($item['visaID']);

            if(!$agent){
                $item['agentID'] = null;
            }
            if ($visa){
                $amount = $visa->total;
                $serviceFee = $visa->service_fee;
                $dubaiFee = $visa->dubai_fee;
            }

            if($applicant){
                $expiryDate = $applicant->passport_expiry;
            }

            if($item['status'] == 'approved'){
                $status = 'appraised';
            }

            if($item['status'] == 'uploaded'){
                $item['status'] = 'new';
            }

            if (!$applicant) {
                $item['applicantID'] = null;
            }

            Application::query()->create([
                'id' => $item['applicationID'],
                'visa_type_id' => $item['visaID'],
                'visa_provider_id' => $item['providerID'],
                'travel_agent_id' => $item['agentID'],
                'application_ref' => $item['reference'],
                'passport_no' => $item['passportNumber'],
                'first_name'  => $item['firstName'],
                'last_name'  => $item['lastName'],
                'expiry_date' => $expiryDate,
                'amount' => $amount,
                'status' => $status,
                'vat' => 0,
                'payment_method' => $item['appType']??'invoice',
                'created_at' => $item['date_created'],
                'updated_at' => $item['date_modified'],
                'service_fee' => $serviceFee,
                'dubai_fee' => $dubaiFee,
                'applicant_id' => $item['applicantID']
            ]);
        }
    }
}
