<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opinion extends Model{
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

    public function scopeActive($query)
    {
        return $query->whereIsActive(1);
    }
}
