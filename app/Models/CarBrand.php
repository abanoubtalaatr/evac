<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
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

    public function carCylinders()
    {
        return $this->hasMany(CarCylinder::class);
    }

    public function carModules()
    {
        return $this->hasMany(CarModule::class);
    }

    public function getNameAttribute()
    {
        return app()->getLocale() =='ar' ? $this->name_ar : $this->name_en;
    }
}
