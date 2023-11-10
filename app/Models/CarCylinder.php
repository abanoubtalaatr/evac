<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarCylinder extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function getStatusClassAttribute()
    {
        return $this->is_active == 1 ? 'green' : 'yellow';
    }

    public function getStatusAttribute()
    {
        return $this->is_active == 1 ? 'active' : 'inactive';
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
