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
    public $boost_type_ids = [];
    public $type = 'flat';
    public $amount;
    public $from_date;
    public $to_date;

    public $isOpen = false;

    protected function rules()
    {
        return [
            'boost_type_ids' => 'required|array|min:1',
            'boost_type_ids.*' => 'exists:boost_types,id',
            'type' => 'required|in:flat,percentage',
            'amount' => 'required|numeric|min:0',
            'from_date' => 'required|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ];
    }

    protected $messages = [
        'boost_type_ids.required' => 'Please select at least one boost type.',
    ];

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
            $this->boost_type_ids = [$log->boost_type_id];
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

        // Overlap check, per selected boost type
        foreach ($this->boost_type_ids as $boostTypeId) {
            if ($this->hasOverlap($boostTypeId)) {
                $boostTypeName = $this->boostTypes->firstWhere('id', $boostTypeId)?->name ?? 'Selected boost type';
                $this->addError(
                    'boost_type_ids',
                    "\"{$boostTypeName}\" already has an overlapping profit log for that date range."
                );
            }
        }

        if ($this->getErrorBag()->has('boost_type_ids')) {
            return;
        }

        $data = [
            'type' => $this->type,
            'amount' => $this->amount,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date ?: null,
        ];

        if ($this->profitLogId) {
            // Editing: update the existing row with the first selected boost type.
            // Any additional boost types picked become new rows.
            $primaryBoostTypeId = $this->boost_type_ids[0];

            UserProfitLog::findOrFail($this->profitLogId)->update(array_merge($data, [
                'boost_type_id' => $primaryBoostTypeId,
            ]));

            foreach (array_slice($this->boost_type_ids, 1) as $boostTypeId) {
                UserProfitLog::create(array_merge($data, [
                    'user_id' => $this->user->id,
                    'boost_type_id' => $boostTypeId,
                ]));
            }

            session()->flash('message', 'Profit log updated successfully.');
        } else {
            foreach ($this->boost_type_ids as $boostTypeId) {
                UserProfitLog::create(array_merge($data, [
                    'user_id' => $this->user->id,
                    'boost_type_id' => $boostTypeId,
                ]));
            }

            session()->flash('message', 'Profit log(s) created successfully.');
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
        $this->boost_type_ids = [];
        $this->type = 'flat';
        $this->amount = '';
        $this->from_date = '';
        $this->to_date = '';
        $this->resetErrorBag();
    }

    /**
     * Check whether the given date range overlaps an existing log
     * for the same user + boost type (excluding the log being edited).
     */
    protected function hasOverlap($boostTypeId): bool
    {
        $toDate = $this->to_date ?: '9999-12-31';

        $query = UserProfitLog::where('user_id', $this->user->id)
            ->where('boost_type_id', $boostTypeId);

        if ($this->profitLogId) {
            $query->where('id', '!=', $this->profitLogId);
        }

        $query->where('from_date', '<=', $toDate)
            ->where(function ($q) {
                $q->whereNull('to_date')
                  ->orWhere('to_date', '>=', $this->from_date);
            });

        return $query->exists();
    }

    public function render()
    {
        return view('livewire.user-profit-log-management');
    }
}
