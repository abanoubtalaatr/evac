<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTransaction extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('excludeDeleted', function ($query) {
            $query->where('status', '!=', 'deleted');
        });
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}
