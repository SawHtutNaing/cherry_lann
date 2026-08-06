<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class CategoryManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $showModal = false;
    public $editingCategory = null;
    public $name = '';
    public $slug = '';
    public $description = '';
    public $is_active = true;
    public $sort_order = 0;

    protected $listeners = ['refreshCategories' => '$refresh'];

    public function createCategory()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function editCategory(Category $category)
    {
        $this->editingCategory = $category;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
        $this->sort_order = $category->sort_order;
        $this->showModal = true;
    }

    public function saveCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->editingCategory?->id),
            ],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($this->editingCategory) {
            $this->editingCategory->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);
            session()->flash('success', 'Category updated successfully!');
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);
            session()->flash('success', 'Category created successfully!');
        }

        $this->closeModal();
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        session()->flash('success', 'Category deleted successfully!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingCategory = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->is_active = true;
        $this->sort_order = 0;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.category-management', [
            'categories' => $categories,
        ]);
    }
}
