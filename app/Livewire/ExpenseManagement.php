<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\ExpenseImage;
use Illuminate\Support\Facades\Storage;

class ExpenseManagement extends Component
{
    use WithFileUploads;

    public $expenses;
    public $expenseCategories;
    public $expense_category_id;
    public $remark;
    public $amount;
    public $date;
    public $expenseId;
    public $isOpen = false;

    // Images: newly selected files pending upload, and images already saved on the expense being edited
    public $newImages = [];
    public $currentImages = [];

    // Filters
    public $filterCategoryIds = [];
    public $filterFromDate;
    public $filterToDate;
    public $totalAmount = 0;

    protected function rules()
    {
        return [
            'expense_category_id' => 'required|exists:expense_categories,id',
            'remark' => 'nullable|string|max:1000',
            'amount' => 'required|integer|min:0',
            'date' => 'required|date',
            'newImages' => 'nullable|array',
            'newImages.*' => 'image|max:5120', // 5MB each
        ];
    }

    public function mount()
    {
        $this->expenseCategories = ExpenseCategory::all();

        // Default filter: current month
        $this->filterFromDate = now()->startOfMonth()->format('Y-m-d');
        $this->filterToDate = now()->endOfMonth()->format('Y-m-d');

        $this->loadExpenses();
    }

    public function loadExpenses()
    {
        $query = Expense::with(['expenseCategory', 'images']);

        if ($this->filterFromDate) {
            $query->whereDate('date', '>=', $this->filterFromDate);
        }

        if ($this->filterToDate) {
            $query->whereDate('date', '<=', $this->filterToDate);
        }

        if (!empty($this->filterCategoryIds)) {
            $query->whereIn('expense_category_id', $this->filterCategoryIds);
        }

        $this->expenses = $query->latest('date')->get();
        $this->totalAmount = $this->expenses->sum('amount');
    }

    public function updatedFilterCategoryIds()
    {
        $this->loadExpenses();
    }

    public function updatedFilterFromDate()
    {
        $this->loadExpenses();
    }

    public function updatedFilterToDate()
    {
        $this->loadExpenses();
    }

    public function resetFilters()
    {
        $this->filterCategoryIds = [];
        $this->filterFromDate = now()->startOfMonth()->format('Y-m-d');
        $this->filterToDate = now()->endOfMonth()->format('Y-m-d');
        $this->loadExpenses();
    }

    public function openModal($expenseId = null)
    {
        $this->resetForm();
        $this->expenseCategories = ExpenseCategory::all();
        if ($expenseId) {
            $expense = Expense::with('images')->findOrFail($expenseId);
            $this->expenseId = $expense->id;
            $this->expense_category_id = $expense->expense_category_id;
            $this->remark = $expense->remark;
            $this->amount = $expense->amount;
            $this->date = $expense->date?->format('Y-m-d');
            $this->currentImages = $expense->images->map(fn ($image) => [
                'id' => $image->id,
                'url' => $image->url,
            ])->toArray();
        } else {
            $this->date = now()->format('Y-m-d');
        }
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'expense_category_id' => $this->expense_category_id,
            'remark' => $this->remark,
            'amount' => $this->amount,
            'date' => $this->date,
        ];

        if ($this->expenseId) {
            $expense = Expense::findOrFail($this->expenseId);
            $expense->update($data);
            session()->flash('message', 'Expense updated successfully.');
        } else {
            $expense = Expense::create($data);
            session()->flash('message', 'Expense created successfully.');
        }

        foreach ($this->newImages as $image) {
            $path = $image->store('expenses', 'public');
            $expense->images()->create(['image_path' => $path]);
        }

        $this->loadExpenses();
        $this->closeModal();
    }

    // ── Remove a newly selected (not-yet-uploaded) image before saving ──
    public function removeNewImage($index)
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
    }

    // ── Delete an already-saved image immediately, without closing the form ──
    public function removeExistingImage($imageId)
    {
        $image = ExpenseImage::find($imageId);

        if ($image && $image->expense_id == $this->expenseId) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $this->currentImages = collect($this->currentImages)
            ->reject(fn ($img) => $img['id'] == $imageId)
            ->values()
            ->toArray();

        $this->loadExpenses();
    }

    public function delete($expenseId)
    {
        try {
            $expense = Expense::with('images')->findOrFail($expenseId);

            foreach ($expense->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $expense->delete(); // expense_images rows cascade-delete via FK

            $this->loadExpenses();
            session()->flash('message', 'Expense deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the Expense.');
        }
    }

    public function resetForm()
    {
        $this->expenseId = null;
        $this->expense_category_id = '';
        $this->remark = '';
        $this->amount = '';
        $this->date = '';
        $this->newImages = [];
        $this->currentImages = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.expense-management', [
            'expenses' => $this->expenses,
        ]);
    }
}
