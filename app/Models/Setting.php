<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    public function getBriefAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->brief_ar : $this->brief_en;
    }

    public function getVideoTitleAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->video_title_ar : $this->video_title_en;
    }

    public function getVideoDescriptionAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->video_description_ar : $this->video_description_en;
    }

    public function getMissionAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->mission_ar : $this->mission_en;
    }

    public function getVisionAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->vision_ar : $this->vision_en;
    }

    public function getObjectiveAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->objective_ar : $this->objective_en;
    }
}
