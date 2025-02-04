<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentVisaPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'visa_type_id',
        'price',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }
}