<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayOffice extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function adminCloseDay()
    {
        return $this->belongsTo(Admin::class,'end_admin_id');
    }

    public function adminRestartDay()
    {
        return $this->belongsTo(Admin::class,'restart_admin_id');
    }
}
