<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubService extends Model
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

    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function scopeActive($query)
    {
        return $query->whereIsActive(1);
    }
}
