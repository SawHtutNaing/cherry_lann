<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfitLog extends Model
{
    protected $fillable = [
        'user_id',
        'boost_type_id',
        'type',
        'amount',
        'from_date',
        'to_date',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boostType()
    {
        return $this->belongsTo(BoostType::class);
    }
}
