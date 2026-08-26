<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DataInputImage extends Model
{
    protected $fillable = ['data_input_id', 'type', 'image_path'];

    public function dataInput()
    {
        return $this->belongsTo(DataInput::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->image_path);
    }
}
