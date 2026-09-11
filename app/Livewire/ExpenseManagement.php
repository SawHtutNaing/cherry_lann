<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Storage;

class ExpenseManagement extends Component
{
    public $expenses;
    public $expenseCategories;
    public $expense_category_id;
    public $remark;
    public $amount;
    public $date;
    public $expenseId;
    public $isOpen = false;

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
            $expense = Expense::findOrFail($expenseId);
            $this->expenseId = $expense->id;
            $this->expense_category_id = $expense->expense_category_id;
            $this->remark = $expense->remark;
            $this->amount = $expense->amount;
            $this->date = $expense->date?->format('Y-m-d');
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
            Expense::findOrFail($this->expenseId)->update($data);
            session()->flash('message', 'Expense updated successfully.');
        } else {
            Expense::create($data);
            session()->flash('message', 'Expense created successfully.');
        }

        $this->loadExpenses();
        $this->closeModal();
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
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.expense-management', [
            'expenses' => $this->expenses,
        ]);
    }
}
