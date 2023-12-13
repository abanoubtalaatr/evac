<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Application extends Model
{
    use HasFactory;

    protected $guarded=['payment_method','visa_type_id','service_fee','dubai_fee','vat','amount','visa_provider_id','passport_no','expiry_date','first_name','last_name','notes','travel_agent_id','application_ref'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('visibleApplications', function ($builder) {
            if (!Auth::check() || Auth::user()->is_owner) {
                return;
            }

            $builder->where(function ($query) {
               return $query->whereDoesntHave('travelAgent')
                    ->orWhereHas('travelAgent', function ($query) {
                        $query->where('is_visible', 1);
                    });
            });
        });
    }

    public function travelAgent()
    {
        return $this->belongsTo(Agent::class,'travel_agent_id');
    }

    public function visaType()
    {
        return $this->belongsTo(VisaType::class, 'visa_type_id');
    }

    public function visaProvider()
    {
        return $this->belongsTo(VisaProvider::class, 'visa_provider_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
