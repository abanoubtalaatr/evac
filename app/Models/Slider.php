<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model{
    use HasFactory;
    protected $guarded=[];


    protected $casts = [
      'is_active' => 'integer',
    ];

    public function getPictureUrlAttribute(){
        return url('uploads/pics/'.$this->picture);
    }

    public function getDescriptionAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->description_ar : $this->description_en;
    }

    public function getTitleAttribute()
    {
        return app()->getLocale() =='ar' ? $this->title_ar : $this->title_en;
    }

    public function scopeActive($query)
    {
        return $query->whereIsActive(1);
    }
}
