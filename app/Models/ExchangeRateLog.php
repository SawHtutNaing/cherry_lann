<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRateLog extends Model
{
    protected $fillable = [
        'service_type_id',
        'amount',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }
}
