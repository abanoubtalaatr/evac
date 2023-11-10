<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model{

    use HasFactory;
    protected $guarded = [];

    public function getPictureUrlAttribute()
    {
        return url('uploads/pics/'.$this->picture);
    }

    public function getTitleAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getDescriptionAttribute()
    {
        return app()->getLocale() ==  'ar' ? $this->desc_ar : $this->desc_en;
    }

    public function getContentAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->content_ar : $this->content_en;
    }
}
