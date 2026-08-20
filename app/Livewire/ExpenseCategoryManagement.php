<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExpenseCategory;

class ExpenseCategoryManagement extends Component
{
    public $expenseCategories;
    public $name;
    public $expenseCategoryId;
    public $isOpen = false;

    // Filters
    public $filterFromDate;
    public $filterToDate;
    public $grandTotal = 0;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:expense_categories,name,' . ($this->expenseCategoryId ?: 'NULL'),
        ];
    }

    public function mount()
    {
        // Default filter: current month
        $this->filterFromDate = now()->startOfMonth()->format('Y-m-d');
        $this->filterToDate = now()->endOfMonth()->format('Y-m-d');

        $this->loadExpenseCategories();
    }

    public function loadExpenseCategories()
    {
        $this->expenseCategories = ExpenseCategory::withSum(['expenses' => function ($query) {
            if ($this->filterFromDate) {
                $query->whereDate('date', '>=', $this->filterFromDate);
            }
            if ($this->filterToDate) {
                $query->whereDate('date', '<=', $this->filterToDate);
            }
        }], 'amount')->get();

        // withSum() produces an "expenses_sum_amount" attribute (null when no matching rows)
        $this->grandTotal = $this->expenseCategories->sum('expenses_sum_amount');
    }

    public function updatedFilterFromDate()
    {
        $this->loadExpenseCategories();
    }

    public function updatedFilterToDate()
    {
        $this->loadExpenseCategories();
    }

    public function resetFilters()
    {
        $this->filterFromDate = now()->startOfMonth()->format('Y-m-d');
        $this->filterToDate = now()->endOfMonth()->format('Y-m-d');
        $this->loadExpenseCategories();
    }

    public function openModal($expenseCategoryId = null)
    {
        $this->resetForm();
        if ($expenseCategoryId) {
            $expenseCategory = ExpenseCategory::findOrFail($expenseCategoryId);
            $this->expenseCategoryId = $expenseCategory->id;
            $this->name = $expenseCategory->name;
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

        $data = ['name' => $this->name];

        if ($this->expenseCategoryId) {
            $expenseCategory = ExpenseCategory::findOrFail($this->expenseCategoryId);
            $expenseCategory->update($data);
            session()->flash('message', 'Expense Category updated successfully.');
        } else {
            ExpenseCategory::create($data);
            session()->flash('message', 'Expense Category created successfully.');
        }

        $this->loadExpenseCategories();
        $this->closeModal();
    }

    public function delete($expenseCategoryId)
    {
        try {
            ExpenseCategory::findOrFail($expenseCategoryId)->delete();
            $this->loadExpenseCategories();
            session()->flash('message', 'Expense Category deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the Expense Category.');
        }
    }

    public function resetForm()
    {
        $this->expenseCategoryId = null;
        $this->name = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.expense-category-management', [
            'expenseCategories' => $this->expenseCategories,
        ]);
    }
}
