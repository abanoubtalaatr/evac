<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model{
    use HasFactory;
    protected $guarded=[];


    public function getPictureUrlAttribute(){
        return url('uploads/pics/'.$this->image);
    }
}
