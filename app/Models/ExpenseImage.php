<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ExpenseImage extends Model
{
    protected $fillable = ['expense_id', 'image_path'];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->image_path);
    }
}
