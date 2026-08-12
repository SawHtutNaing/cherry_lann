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
        $this->serviceTypes = ServiceType::with('boostTypes')->get();
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
            session()->flash('message', 'Service Type updated successfully.');
        } else {
            $serviceType = ServiceType::create($data);
            session()->flash('message', 'Service Type created successfully.');
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
            session()->flash('message', 'Service Type deleted successfully.');
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
