<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Region;

class RegionManagement extends Component
{
    public $regions;
    public $name;
    public $regionId;
    public $isOpen = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:regions,name,' . ($this->regionId ?: 'NULL'),
        ];
    }

    public function mount()
    {
        $this->loadRegions();
    }

    public function loadRegions()
    {
        $this->regions = Region::orderByDesc('is_active')
            ->orderBy('name')
            ->get();
    }

    public function openModal($regionId = null)
    {
        $this->resetForm();
        if ($regionId) {
            $region = Region::findOrFail($regionId);
            $this->regionId = $region->id;
            $this->name = $region->name;
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
        try {
            $this->validate();
            $data = ['name' => $this->name];

            if ($this->regionId) {
                $region = Region::findOrFail($this->regionId);
                $region->update($data);
            } else {
                $data['is_active'] = true;
                Region::create($data);
            }

            $this->loadRegions();
            $this->closeModal();
            session()->flash('success', 'Region ' . ($this->regionId ? 'updated' : 'created') . ' successfully!');
        } catch (\Exception $e) {
            $this->addError('general', 'An error occurred while saving the Region.');
        }
    }

    public function delete($regionId)
    {
        try {
            Region::findOrFail($regionId)->delete();
            $this->loadRegions();
            session()->flash('success', 'Region deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the Region.');
        }
    }

    public function toggleStatus($regionId)
    {
        $region = Region::findOrFail($regionId);
        $region->update(['is_active' => ! $region->is_active]);

        session()->flash('success', 'Region ' . ($region->is_active ? 'enabled' : 'disabled') . ' successfully!');

        $this->loadRegions();
    }

    public function resetForm()
    {
        $this->regionId = null;
        $this->name = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.region-management', [
            'regions' => $this->regions,
        ]);
    }
}
