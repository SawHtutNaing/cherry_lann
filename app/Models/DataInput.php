<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\BoostStatus;

class DataInput extends Model
{
    protected $fillable = [
        'page_name', 'customer_name', 'phone', 'status', 'user_id',
        'total_amount', 'is_remark', 'remark',
        'client_side_image', 'service_side_image',
    ];

    protected $casts = [
        'status'       => BoostStatus::class,
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(DataInputItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
