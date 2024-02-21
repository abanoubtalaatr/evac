<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $jsonFilePath = database_path('seeders/agents.json');
        $jsonContent = file_get_contents($jsonFilePath);

        $dataArray = json_decode($jsonContent, true);
        foreach ($dataArray as $item){
            $status = $item['status'];

            if($item['status'] == 3 || $item['status'] == 2){
                $status = 0;
            }

            Agent::query()->create([
                'id' => $item['agentID'],
                'name' => $item['name'],
                'account_number' =>'',
                'company_name' => '',
                'address' =>  $item['address'],
                'telephone' => $item['phone'],
                'mobile' => $item['phone'],
                'email' => $item['email'],
                'owner_name' => '',
                'contact_name' => $item['contactPersonName'],
                'iata_no' => $item['IATARegNo'],
                'finance_no' => '',
                'is_active' => $status,
                'created_at' => $item['created_date'],
                'updated_at' => $item['modified_date'],
             ]);
        }
    }
}
