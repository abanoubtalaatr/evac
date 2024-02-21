<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model{
    protected $guarded=[];


    public function getImageUrlAttribute(){
        return asset('assets_ar/imgs/home/bell.png');
    }

    public function getContentAttribute()
    {
        return app()->getLocale() =='ar' ? $this->content_ar : $this->content_en ;
    }

    public function getTitleAttribute()
    {
        return app()->getLocale() =='en' ? $this->title_ar : $this->title_en ;
    }
}
