<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'logo_path', 'hero_title', 'hero_subtitle', 'hero_button_text',
        'hero_button_link', 'hero_image_path', 'about_title', 'about_subtitle',
        'facebook_url', 'viber_url', 'footer_text',
    ];

    public function getLogoUrlAttribute(): string
    {
        return $this->logo_path
            ? Storage::disk('public')->url($this->logo_path)
            : asset('images/logo.jpeg');
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->hero_image_path ? Storage::disk('public')->url($this->hero_image_path) : null;
    }

    // There is only ever one row.
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'hero_title' => 'Boost Your Digital Presence',
        ]);
    }
}
