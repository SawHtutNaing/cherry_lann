<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BoostType;
use App\Models\DataInput;
use App\Models\DataInputItem;
use Illuminate\Support\Facades\DB;

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
        $this->boostTypes = BoostType::orderByDesc('is_active')
            ->orderBy('name')
            ->get();
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
                $data['is_active'] = true;
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
            DB::transaction(function () use ($boostTypeId) {
                // Soft-delete every Data Input that used this service type, so they
                // drop out of the lists along with it, then soft-delete the type itself.
                $dataInputIds = DataInputItem::where('boost_type_id', $boostTypeId)
                    ->pluck('data_input_id')
                    ->unique();

                if ($dataInputIds->isNotEmpty()) {
                    DataInput::whereIn('id', $dataInputIds)->delete();
                }

                BoostType::findOrFail($boostTypeId)->delete();
            });

            $this->loadBoostTypes();
            session()->flash('success', 'Boost Type deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the Boost Type.');
        }
    }

    // ---------- Status toggle ----------

    public function toggleStatus($boostTypeId)
    {
        $boostType = BoostType::findOrFail($boostTypeId);
        $boostType->update(['is_active' => ! $boostType->is_active]);

        session()->flash('success', 'Boost Type ' . ($boostType->is_active ? 'enabled' : 'disabled') . ' successfully!');

        $this->loadBoostTypes();
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
