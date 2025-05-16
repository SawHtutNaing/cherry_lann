<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\BoostStatus;

class DataInput extends Model
{
    protected $fillable = ['page_name', 'customer_name' , 'phone',  'boost_type_id', 'start_date', 'amount', 'status', 'user_id' ,'mm_kyat' , 'total_amount'
,'discount' , 'is_remark' ,'remark'
];
    protected $casts = [
        'status' => BoostStatus::class, // Automatically cast status as an enum
        'start_date' => 'date', // Casts to Carbon instance
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
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
