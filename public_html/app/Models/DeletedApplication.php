<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DeletedApplication extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('visibleDeletedApplications', function ($builder) {
            if (!Auth::check() || Auth::user()->is_owner) {
                return;
            }

            $builder->where(function ($query) {
                return $query->whereDoesntHave('agent')
                    ->orWhereHas('agent', function ($query) {
                        $query->where('is_visible', 1);
                    });
            });
        });
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
