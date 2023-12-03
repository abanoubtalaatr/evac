<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Agent extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('visibleAgents', function ($builder) {

            if (Auth::check() && Auth::user()->is_owner) {
                // If the user is an owner, do nothing (all agents are included)
            } else {
                // If not an owner, apply conditions for non-owners
                $builder->where('is_visible', true);
            }
        });
    }


    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    protected $casts = [
      'is_active' => 'boolean',
    ];
}
