<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Agent extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function scopeOwner($query)
    {
        $query->where('is_visible', 1);
    }
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    protected $casts = [
      'is_active' => 'boolean',
    ];
}
