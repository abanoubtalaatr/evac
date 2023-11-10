<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    use HasFactory;

    protected $guarded=[];

//    protected $appends = ['title'];
//
//    public function getTitleAttribute()
//    {
//        return app()->getLocale() == 'ar' ? $this->title_ar : $this->title_en;
//    }

    public function getStatusClassAttribute()
    {
        return $this->is_active == 1 ? 'green' : 'yellow';
    }

    public function getStatusAttribute()
    {
        return $this->is_active == 1 ? 'active' : 'inactive';
    }

    public function subService()
    {
        return $this->belongsTo(SubService::class);
    }

    public function subServices()
    {
        return $this->hasMany(SubService::class);
    }

    public function getImageAttribute($value)
    {
        return url('uploads/pics/'.$value);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active' , 1);
    }

    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
}
