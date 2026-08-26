<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    protected $fillable = ['user_id', 'amount', 'date', 'remark'];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(VisaImage::class);
    }

    // e.g. $visa->formatted_amount => "1,250"
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount);
    }
}
