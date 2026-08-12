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

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:expense_categories,name,' . ($this->expenseCategoryId ?: 'NULL'),
        ];
    }

    public function mount()
    {
        $this->loadExpenseCategories();
    }

    public function loadExpenseCategories()
    {
        $this->expenseCategories = ExpenseCategory::all();
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
