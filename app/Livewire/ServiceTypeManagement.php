<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServiceType;
use App\Models\BoostType;
use App\Models\ExchangeRateLog;

class ServiceTypeManagement extends Component
{
    // Service type list & form
    public $serviceTypes;
    public $boostTypes;

    public $name;
    public $type = 'mmk';
    public $serviceTypeId;
    public $selectedBoostTypes = [];

    public $isOpen = false;

    // Exchange rate log modal
    public $isExchangeModalOpen = false;
    public $exchangeServiceTypeId;
    public $exchangeServiceTypeName;
    public $exchangeRateLogs = [];

    public $exchangeLogId;
    public $exchangeAmount;
    public $exchangeStartDate;
    public $exchangeEndDate;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:service_types,name,' . ($this->serviceTypeId ?: 'NULL'),
            'type' => 'required|in:mmk,dollar',
            'selectedBoostTypes' => 'array',
            'selectedBoostTypes.*' => 'exists:boost_types,id',
        ];
    }

    protected function exchangeRules()
    {
        return [
            'exchangeAmount' => 'required|numeric|min:0',
            'exchangeStartDate' => 'required|date',
            'exchangeEndDate' => 'nullable|date|after_or_equal:exchangeStartDate',
        ];
    }

    public function mount()
    {
        $this->loadServiceTypes();
        $this->boostTypes = BoostType::all();
    }

    public function loadServiceTypes()
    {
        $this->serviceTypes = ServiceType::with('boostTypes')
            ->orderByDesc('is_active')
            ->orderBy('sort_no')
            ->get();
    }

    // ---------- Service Type CRUD ----------

    public function openModal($serviceTypeId = null)
    {
        $this->resetForm();
        if ($serviceTypeId) {
            $serviceType = ServiceType::with('boostTypes')->findOrFail($serviceTypeId);
            $this->serviceTypeId = $serviceType->id;
            $this->name = $serviceType->name;
            $this->type = $serviceType->type;
            $this->selectedBoostTypes = $serviceType->boostTypes->pluck('id')->toArray();
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
            'name' => $this->name,
            'type' => $this->type,
        ];

        if ($this->serviceTypeId) {
            $serviceType = ServiceType::findOrFail($this->serviceTypeId);
            $serviceType->update($data);
            session()->flash('message', 'Service Group updated successfully.');
        } else {
            $data['sort_no'] = (ServiceType::max('sort_no') ?? 0) + 1;
            $data['is_active'] = true;
            $serviceType = ServiceType::create($data);
            session()->flash('message', 'Service Group created successfully.');
        }

        $serviceType->boostTypes()->sync($this->selectedBoostTypes);

        $this->loadServiceTypes();
        $this->closeModal();
    }

    public function delete($serviceTypeId)
    {
        try {
            ServiceType::findOrFail($serviceTypeId)->delete();
            $this->loadServiceTypes();
            session()->flash('message', 'Service Group deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the Service Type.');
        }
    }

    public function resetForm()
    {
        $this->serviceTypeId = null;
        $this->name = '';
        $this->type = 'mmk';
        $this->selectedBoostTypes = [];
        $this->resetErrorBag();
    }

    // ---------- Status toggle ----------

    public function toggleStatus($serviceTypeId)
    {
        $serviceType = ServiceType::findOrFail($serviceTypeId);
        $serviceType->update(['is_active' => ! $serviceType->is_active]);

        session()->flash('message', 'Service Group ' . ($serviceType->is_active ? 'enabled' : 'disabled') . ' successfully.');

        $this->loadServiceTypes();
    }

    // ---------- Sorting ----------

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            ServiceType::where('id', $id)->update(['sort_no' => $index + 1]);
        }

        $this->loadServiceTypes();
    }

    public function moveUp($serviceTypeId)
    {
        $current = ServiceType::findOrFail($serviceTypeId);

        $previous = ServiceType::where('is_active', $current->is_active)
            ->where('sort_no', '<', $current->sort_no)
            ->orderByDesc('sort_no')
            ->first();

        if ($previous) {
            $currentSort = $current->sort_no;
            $current->update(['sort_no' => $previous->sort_no]);
            $previous->update(['sort_no' => $currentSort]);
        }

        $this->loadServiceTypes();
    }

    public function moveDown($serviceTypeId)
    {
        $current = ServiceType::findOrFail($serviceTypeId);

        $next = ServiceType::where('is_active', $current->is_active)
            ->where('sort_no', '>', $current->sort_no)
            ->orderBy('sort_no')
            ->first();

        if ($next) {
            $currentSort = $current->sort_no;
            $current->update(['sort_no' => $next->sort_no]);
            $next->update(['sort_no' => $currentSort]);
        }

        $this->loadServiceTypes();
    }

    // ---------- Exchange Rate Log CRUD ----------

    public function openExchangeModal($serviceTypeId)
    {
        $serviceType = ServiceType::findOrFail($serviceTypeId);

        $this->exchangeServiceTypeId = $serviceType->id;
        $this->exchangeServiceTypeName = $serviceType->name;

        $this->resetExchangeForm();
        $this->loadExchangeRateLogs();

        $this->isExchangeModalOpen = true;
    }

    public function loadExchangeRateLogs()
    {
        $this->exchangeRateLogs = ExchangeRateLog::where('service_type_id', $this->exchangeServiceTypeId)
            ->orderByDesc('start_date')
            ->get();
    }

    public function editExchangeRate($logId)
    {
        $log = ExchangeRateLog::findOrFail($logId);
        $this->exchangeLogId = $log->id;
        $this->exchangeAmount = $log->amount;
        $this->exchangeStartDate = $log->start_date->format('Y-m-d');
        $this->exchangeEndDate = $log->end_date ? $log->end_date->format('Y-m-d') : null;
    }

    public function saveExchangeRate()
    {
        $this->validate($this->exchangeRules());

        $data = [
            'service_type_id' => $this->exchangeServiceTypeId,
            'amount' => $this->exchangeAmount,
            'start_date' => $this->exchangeStartDate,
            'end_date' => $this->exchangeEndDate ?: null,
        ];

        if ($this->exchangeLogId) {
            ExchangeRateLog::findOrFail($this->exchangeLogId)->update($data);
            session()->flash('message', 'Exchange rate updated successfully.');
        } else {
            ExchangeRateLog::create($data);
            session()->flash('message', 'Exchange rate added successfully.');
        }

        $this->loadExchangeRateLogs();
        $this->resetExchangeForm();
    }

    public function deleteExchangeRate($logId)
    {
        try {
            ExchangeRateLog::findOrFail($logId)->delete();
            $this->loadExchangeRateLogs();
            session()->flash('message', 'Exchange rate deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the exchange rate.');
        }
    }

    public function closeExchangeModal()
    {
        $this->isExchangeModalOpen = false;
        $this->exchangeServiceTypeId = null;
        $this->exchangeServiceTypeName = null;
        $this->exchangeRateLogs = [];
        $this->resetExchangeForm();
    }

    public function resetExchangeForm()
    {
        $this->exchangeLogId = null;
        $this->exchangeAmount = '';
        $this->exchangeStartDate = '';
        $this->exchangeEndDate = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.service-type-management', [
            'serviceTypes' => $this->serviceTypes,
        ]);
    }
}
