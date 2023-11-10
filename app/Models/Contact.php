<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model{
    use HasFactory;
    protected $guarded = [];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function getRepliedAtAttribute(){
        return (strtotime($this->created_at) === strtotime($this->updated_at))? '--' : $this->updated_at;
    }

    public function scopeUnreplied($query){
        return $query->whereStatus('unreplied');
    }

     public static function boot(){
	    parent::boot();
	    static::creating(function($item) {
	        $item->status = 'unreplied';
	    });

	}

}
