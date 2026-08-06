<?php

namespace App\Livewire;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class SiteSettingManagement extends Component
{
    public SiteSetting $setting;

    public $logo_path = '';
    public $hero_title = '';
    public $hero_subtitle = '';
    public $hero_button_text = '';
    public $hero_button_link = '';
    public $hero_image_path = '';
    public $about_title = '';
    public $about_subtitle = '';
    public $facebook_url = '';
    public $viber_url = '';
    public $footer_text = '';

    public ?string $originalLogoPath = null;
    public ?string $originalHeroImagePath = null;

    protected function rules(): array
    {
        return [
            'hero_title'       => 'required|string|max:255',
            'hero_subtitle'    => 'nullable|string',
            'hero_button_text' => 'nullable|string|max:100',
            'hero_button_link' => 'nullable|string|max:255',
            'about_title'      => 'nullable|string|max:255',
            'about_subtitle'   => 'nullable|string',
            'facebook_url'     => 'nullable|url|max:255',
            'viber_url'        => 'nullable|url|max:255',
            'footer_text'      => 'nullable|string|max:255',
        ];
    }

    public function mount(): void
    {
        $this->setting = SiteSetting::current();
        $this->fillFromModel();
    }

    private function fillFromModel(): void
    {
        $this->logo_path        = $this->setting->logo_path ?? '';
        $this->hero_title       = $this->setting->hero_title ?? '';
        $this->hero_subtitle    = $this->setting->hero_subtitle ?? '';
        $this->hero_button_text = $this->setting->hero_button_text ?? '';
        $this->hero_button_link = $this->setting->hero_button_link ?? '';
        $this->hero_image_path  = $this->setting->hero_image_path ?? '';
        $this->about_title      = $this->setting->about_title ?? '';
        $this->about_subtitle   = $this->setting->about_subtitle ?? '';
        $this->facebook_url     = $this->setting->facebook_url ?? '';
        $this->viber_url        = $this->setting->viber_url ?? '';
        $this->footer_text      = $this->setting->footer_text ?? '';

        $this->originalLogoPath      = $this->setting->logo_path;
        $this->originalHeroImagePath = $this->setting->hero_image_path;
    }

    public function save(): void
    {
        $this->validate();

        $this->setting->update([
            'logo_path'        => $this->logo_path ?: null,
            'hero_title'       => $this->hero_title,
            'hero_subtitle'    => $this->nullIfBlank($this->hero_subtitle),
            'hero_button_text' => $this->nullIfBlank($this->hero_button_text),
            'hero_button_link' => $this->nullIfBlank($this->hero_button_link),
            'hero_image_path'  => $this->hero_image_path ?: null,
            'about_title'      => $this->nullIfBlank($this->about_title),
            'about_subtitle'   => $this->nullIfBlank($this->about_subtitle),
            'facebook_url'     => $this->nullIfBlank($this->facebook_url),
            'viber_url'        => $this->nullIfBlank($this->viber_url),
            'footer_text'      => $this->nullIfBlank($this->footer_text),
        ]);

        // Only delete replaced files after the new paths are safely saved.
        if ($this->originalLogoPath && $this->originalLogoPath !== $this->logo_path) {
            Storage::disk('public')->delete($this->originalLogoPath);
        }
        if ($this->originalHeroImagePath && $this->originalHeroImagePath !== $this->hero_image_path) {
            Storage::disk('public')->delete($this->originalHeroImagePath);
        }

        $this->setting->refresh();
        $this->fillFromModel();

        session()->flash('success', 'Settings updated successfully.');
    }

    public function setLogoPath($path): void
    {
        if ($this->logo_path && $this->logo_path !== $this->originalLogoPath && $this->logo_path !== $path) {
            Storage::disk('public')->delete($this->logo_path);
        }
        $this->logo_path = $path;
    }

    public function removeLogo(): void
    {
        if ($this->logo_path && $this->logo_path !== $this->originalLogoPath) {
            Storage::disk('public')->delete($this->logo_path);
        }
        $this->logo_path = '';
    }

    public function setHeroImagePath($path): void
    {
        if ($this->hero_image_path && $this->hero_image_path !== $this->originalHeroImagePath && $this->hero_image_path !== $path) {
            Storage::disk('public')->delete($this->hero_image_path);
        }
        $this->hero_image_path = $path;
    }

    public function removeHeroImage(): void
    {
        if ($this->hero_image_path && $this->hero_image_path !== $this->originalHeroImagePath) {
            Storage::disk('public')->delete($this->hero_image_path);
        }
        $this->hero_image_path = '';
    }

    private function nullIfBlank($value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    public function render()
    {
        return view('livewire.site-setting-management');
    }
}
