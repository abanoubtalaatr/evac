<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class);
    }

    public function carModule()
    {
        return $this->belongsTo(CarModule::class);
    }
}
