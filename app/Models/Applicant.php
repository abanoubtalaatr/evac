<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $casts = [
        'passport_expiry' => 'datetime',
    ];

    protected $dates = [
        'passport_expiry',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Serialize the date to a format for array or JSON representation.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }
}
