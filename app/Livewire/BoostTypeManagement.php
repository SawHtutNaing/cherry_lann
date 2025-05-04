<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BoostType;

class BoostTypeManagement extends Component
{
    public $boostTypes;
    public $name;
    public $boostTypeId;
    public $isOpen = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:boost_types,name,' . ($this->boostTypeId ?: 'NULL'),
        ];
    }

    public function mount()
    {
        $this->loadBoostTypes();
    }

    public function loadBoostTypes()
    {
        $this->boostTypes = BoostType::all();
    }

    public function openModal($boostTypeId = null)
    {
        $this->resetForm();
        if ($boostTypeId) {
            $boostType = BoostType::findOrFail($boostTypeId);
            $this->boostTypeId = $boostType->id;
            $this->name = $boostType->name;
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
            if ($this->boostTypeId) {
                $boostType = BoostType::findOrFail($this->boostTypeId);
                $boostType->update($data);
            } else {
                BoostType::create($data);
            }
            $this->loadBoostTypes();
            $this->closeModal();
            session()->flash('success', 'Boost Type ' . ($this->boostTypeId ? 'updated' : 'created') . ' successfully!');
        } catch (\Exception $e) {
            $this->addError('general', 'An error occurred while saving the Boost Type.');
        }
    }

    public function delete($boostTypeId)
    {
        try {
            BoostType::findOrFail($boostTypeId)->delete();
            $this->loadBoostTypes(); // Fixed typo
            session()->flash('success', 'Boost Type deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the Boost Type.');
        }
    }

    public function resetForm()
    {
        $this->boostTypeId = null;
        $this->name = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.boost-type-management', [
            'boostTypes' => $this->boostTypes,
        ]);
    }
}
