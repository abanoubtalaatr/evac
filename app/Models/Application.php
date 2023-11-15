<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function travelAgent()
    {
        return $this->belongsTo(Agent::class,'travel_agent_id');
    }

    public function visaType()
    {
        return $this->belongsTo(VisaType::class, 'visa_type_id');
    }

    public function visaProvider()
    {
        return $this->belongsTo(VisaProvider::class, 'visa_provider_id');
    }
}
