<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoostType extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class, 'boost_type_service_type');
    }

    public function profitLogs()
    {
        return $this->hasMany(UserProfitLog::class);
    }
}
