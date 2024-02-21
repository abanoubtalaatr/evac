<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getStatusClassAttribute()
    {
        return $this->is_active == 1 ? 'green' : 'yellow';
    }

    public function getStatusAttribute()
    {
        return $this->is_active == 1 ? 'active' : 'inactive';
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar == 'default_user_avatar.png' ? asset('frontAssets/assets_' . app()->getLocale() . '/imgs/home/logo.svg')
            :
            url('uploads/pics/' . $this->avatar);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->default_language = app()->getLocale();
            $query->resend_verification_code_num = 1;
        });
    }


    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    //JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
