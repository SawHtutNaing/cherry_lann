<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\BoostStatus;

class DataInput extends Model
{
    protected $fillable = ['page_name', 'boost_type_id', 'start_date', 'amount', 'status', 'user_id' ,'mm_kyat' , 'total_amount'];
    protected $casts = [
        'status' => BoostStatus::class, // Automatically cast status as an enum
    ];

    public function boostType()
    {
        return $this->belongsTo(BoostType::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
