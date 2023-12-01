<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\Application;

class ApplicantService
{
    public function update($data)
    {
        $applicant = Applicant::query()->find($data['applicant_id']);

        if($applicant){
            $applicant->update([
                'name' => $data['first_name'],
                'surname' => $data['last_name'],
                'agent_id' => $data['travel_agent_id']??null,
                'passport_no' => $data['passport_no'],
                'passport_expiry' => $data['expiry_date']
            ]);
        }
    }

    public function create($data)
    {
        $applicant = Applicant::query()
            ->where('name', $data['first_name'])
            ->where('surname', $data['last_name'])
            ->first();

        if(!$applicant){
            return Applicant::query()->create([
                'name' => $data['first_name'],
                'surname' => $data['last_name'],
                'agent_id' => $data['travel_agent_id']??null,
                'passport_no' => $data['passport_no'],
                'passport_expiry' => $data['expiry_date']
            ]);
        }
        return $applicant;
    }
}
