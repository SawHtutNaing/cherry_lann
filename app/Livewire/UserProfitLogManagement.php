<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\BoostType;
use App\Models\UserProfitLog;

class UserProfitLogManagement extends Component
{
    public User $user;
    public $profitLogs;
    public $boostTypes;

    public $profitLogId;
    public $boost_type_id;
    public $type = 'flat';
    public $amount;
    public $from_date;
    public $to_date;

    public $isOpen = false;

    protected function rules()
    {
        return [
            'boost_type_id' => 'required|exists:boost_types,id',
            'type' => 'required|in:flat,percentage',
            'amount' => 'required|numeric|min:0',
            'from_date' => 'required|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ];
    }

    public function mount(User $user)
    {
        if (auth()->user()->role !== 'super_admin') {
            return redirect()->route('users.index');
        }

        $this->user = $user;
        $this->boostTypes = BoostType::all();
        $this->loadProfitLogs();
    }

    public function loadProfitLogs()
    {
        $this->profitLogs = UserProfitLog::with('boostType')
            ->where('user_id', $this->user->id)
            ->orderByDesc('from_date')
            ->get();
    }

    public function openModal($profitLogId = null)
    {
        $this->resetForm();
        if ($profitLogId) {
            $log = UserProfitLog::findOrFail($profitLogId);
            $this->profitLogId = $log->id;
            $this->boost_type_id = $log->boost_type_id;
            $this->type = $log->type;
            $this->amount = $log->amount;
            $this->from_date = $log->from_date->format('Y-m-d');
            $this->to_date = $log->to_date ? $log->to_date->format('Y-m-d') : null;
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
            'user_id' => $this->user->id,
            'boost_type_id' => $this->boost_type_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date ?: null,
        ];

        if ($this->profitLogId) {
            UserProfitLog::findOrFail($this->profitLogId)->update($data);
            session()->flash('message', 'Profit log updated successfully.');
        } else {
            UserProfitLog::create($data);
            session()->flash('message', 'Profit log created successfully.');
        }

        $this->loadProfitLogs();
        $this->closeModal();
    }

    public function delete($profitLogId)
    {
        try {
            UserProfitLog::findOrFail($profitLogId)->delete();
            $this->loadProfitLogs();
            session()->flash('message', 'Profit log deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the profit log.');
        }
    }

    public function resetForm()
    {
        $this->profitLogId = null;
        $this->boost_type_id = '';
        $this->type = 'flat';
        $this->amount = '';
        $this->from_date = '';
        $this->to_date = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.user-profit-log-management');
    }
}
