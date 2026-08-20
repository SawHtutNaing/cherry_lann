<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $fillable = ['name', 'type', 'sort_no'];

    public function boostTypes()
    {
        return $this->belongsToMany(BoostType::class, 'boost_type_service_type');
    }

    public function exchangeRateLogs()
    {
        return $this->hasMany(ExchangeRateLog::class)->orderBy('start_date');
    }
}
