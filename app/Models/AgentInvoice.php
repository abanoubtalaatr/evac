<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentInvoice extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
