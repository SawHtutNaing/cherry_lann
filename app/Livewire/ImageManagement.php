<?php

namespace App\Livewire;

use App\Models\CmsImage;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ImageManagement extends Component
{
    public $categoryFilter = '';

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    public $category_id = '';
    public $title = '';
    public $alt_text = '';
    public $image_path = '';
    public $description = '';
    public $link_url = '';
    public $is_active = true;
    public $sort_order = 0;

    // The path that is *actually saved on the record* right now.
    // Used so we only ever delete files that are safe to delete:
    // either the old one (after a successful save) or a freshly
    // uploaded-but-never-saved one (on cancel/remove).
    public ?string $originalImagePath = null;

    protected function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'alt_text'    => 'nullable|string|max:255',
            'image_path'  => 'required|string',
            'description' => 'nullable|string',
            'link_url'    => 'nullable|url|max:255',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ];
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->categoryFilter, fn ($q) => $q->where('id', $this->categoryFilter))
            ->with(['images' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return view('livewire.image-management', [
            'categoriesWithImages' => $categories,
            'allCategories'        => Category::orderBy('name')->get(),
        ]);
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id): void
    {
        $image = CmsImage::findOrFail($id);

        $this->editingId    = $image->id;
        $this->category_id  = $image->category_id;
        $this->title        = $image->title;
        $this->alt_text     = $image->alt_text;
        $this->image_path   = $image->image_path;
        $this->description  = $image->description;
        $this->link_url     = $image->link_url;
        $this->is_active    = $image->is_active;
        $this->sort_order   = $image->sort_order;

        $this->originalImagePath = $image->image_path;

        $this->isEditing = true;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function closeModal(): void
    {
        // If a new file was uploaded this session but never saved,
        // it's an orphan on disk — safe to delete. The original
        // (still-saved) file is never touched here.
        $this->discardUnsavedUpload();

        $this->showModal = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'category_id' => $this->category_id,
            'title'       => $this->title,
            'alt_text'    => $this->alt_text,
            'image_path'  => $this->image_path,
            'description' => $this->description,
            'link_url'    => $this->normalizedLinkUrl(),
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order ?: 0,
        ];

        if ($this->isEditing && $this->editingId) {
            CmsImage::findOrFail($this->editingId)->update($data);

            // Only now, after the new path is safely persisted, is it
            // safe to remove the old file.
            if ($this->originalImagePath && $this->originalImagePath !== $this->image_path) {
                Storage::disk('public')->delete($this->originalImagePath);
            }

            session()->flash('success', 'Image updated successfully.');
        } else {
            CmsImage::create($data);
            session()->flash('success', 'Image created successfully.');
        }

        $this->closeModalWithoutDiscarding();
    }

    public function delete($id): void
    {
        $image = CmsImage::findOrFail($id);

        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        session()->flash('success', 'Image deleted successfully.');
    }

    public function toggleActive($id): void
    {
        $image = CmsImage::findOrFail($id);
        $image->update(['is_active' => ! $image->is_active]);
    }

    // Called by JS (Alpine) right after a successful XHR upload
    public function setImagePath($path): void
    {
        // If a *previous, still-unsaved* upload from this session exists,
        // it's now an orphan (replaced by this one) — safe to delete.
        // The original saved file is never deleted here.
        if ($this->image_path
            && $this->image_path !== $this->originalImagePath
            && $this->image_path !== $path) {
            Storage::disk('public')->delete($this->image_path);
        }

        $this->image_path = $path;
    }

    public function removeImage(): void
    {
        // Only delete from disk if this is an unsaved upload made during
        // this session. Never delete the currently-saved original here —
        // that only happens once a save actually replaces it.
        if ($this->image_path && $this->image_path !== $this->originalImagePath) {
            Storage::disk('public')->delete($this->image_path);
        }

        $this->image_path = '';
    }

    private function discardUnsavedUpload(): void
    {
        if ($this->image_path && $this->image_path !== $this->originalImagePath) {
            Storage::disk('public')->delete($this->image_path);
        }
    }

    private function closeModalWithoutDiscarding(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function normalizedLinkUrl(): ?string
    {
        $value = trim((string) $this->link_url);

        return $value === '' ? null : $value;
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'originalImagePath', 'category_id', 'title', 'alt_text',
            'image_path', 'description', 'link_url', 'sort_order',
        ]);
        $this->is_active = true;
        $this->resetValidation();
    }
}
