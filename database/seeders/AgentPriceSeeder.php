<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\VisaType;
use App\Models\AgentVisaPrice;
use Illuminate\Database\Seeder;

class AgentPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $visas = VisaType::all();

        foreach($visas as $visa)
        {
            $agents = Agent::all();

            foreach($agents as $agent)
            {
                $agentVisaPrice = AgentVisaPrice::where('agent_id', $agent->id)->where('visa_type_id')->first();
                if($agentVisaPrice){
                    continue;
                }
                AgentVisaPrice::create([
                    'visa_type_id' => $visa->id,
                    'agent_id' => $agent->id,
                    'price' => $visa->service_fee
                ]);
            }
               
        }
    }
}
