<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Agent extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeOwner($query)
    {
        $query->where('is_visible', 1);
    }

    public function scopeIsActive($query)
    {
        $query->where('is_active', 1);
    }
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function serviceTransactions()
    {
        return $this->hasMany(ServiceTransaction::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'travel_agent_id');
    }

    public function agentVisaPrices()
    {
        return $this->hasMany(AgentVisaPrice::class);
    }
}
