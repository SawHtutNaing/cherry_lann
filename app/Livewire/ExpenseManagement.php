<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Expense;
use App\Models\ExpenseCategory;

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
        $this->loadExpenses();
        $this->expenseCategories = ExpenseCategory::all();
    }

    public function loadExpenses()
    {
        $this->expenses = Expense::with('expenseCategory')->latest()->get();
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
            $expense = Expense::findOrFail($this->expenseId);
            $expense->update($data);
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
            Expense::findOrFail($expenseId)->delete();
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
