<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VisaImage extends Model
{
    protected $fillable = ['visa_id', 'image_path'];

    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->image_path);
    }
}
