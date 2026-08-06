<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(CmsImage::class)->orderBy('sort_order');
    }

    public function activeImages(): HasMany
    {
        return $this->images()->where('is_active', true);
    }
}
