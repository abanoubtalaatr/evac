<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory;
    use Notifiable;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function subServices()
    {
        return $this->belongsToMany(SubService::class, 'order_sub_services');
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function oil()
    {
        return $this->belongsTo(Oil::class);
    }

    public function oilBrand()
    {
        return $this->belongsTo(OilBrand::class);
    }


    public function getStatusClassAttribute()
    {
        if($this->status =='approved'){
            return 'green';
        }
        if($this->status =='not_approved'){
            return 'yellow';
        }
        if($this->status =='finished') {
            return 'primary';
        }
        if($this->status == 'rejected') {

            return 'rejected';
        }
    }

    public function getStatusAttribute($value)
    {
        if ($value == 'approved') {
            return 'approved';
        }

        if ($value == 'not_approved') {
            return 'not_approved';
        }

        if ($value == 'finished') {
            return 'finished';
        }

        if ($value == 'reject') {
            return 'rejected';
        }
    }
}
