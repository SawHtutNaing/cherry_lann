<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\BoostStatus;

class DataInput extends Model
{
    protected $fillable = [
        'page_name', 'customer_name', 'phone', 'status', 'user_id',
        'total_amount', 'is_remark', 'remark',
        'region_id',
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

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function images()
    {
        return $this->hasMany(DataInputImage::class);
    }

    public function clientImages()
    {
        return $this->hasMany(DataInputImage::class)->where('type', 'client');
    }

    public function serviceImages()
    {
        return $this->hasMany(DataInputImage::class)->where('type', 'service');
    }
}
