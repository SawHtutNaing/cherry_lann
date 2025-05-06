<?php

namespace App\Livewire;

use App\BoostStatus;
use Livewire\Component;
use App\Models\DataInput;
use App\Models\BoostType;
use Illuminate\Support\Facades\Auth;

class DataInputForm extends Component
{
    public $customer_name;
    public $page_name;
    public $phone;
    public $boost_type_id;
    public $start_date;
    public $amount;
    public $mm_kyat;
    public $total_amount;
    public $status;
    public $dataInputId;
    public $boostTypes;

    protected function rules()
    {
        return [
            'customer_name' => 'nullable|string|max:255',
            'page_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'boost_type_id' => 'required|exists:boost_types,id',
            'start_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'mm_kyat' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:1,2,3', // Updated to include Pending (3)
        ];
    }

    public function mount($dataInputId = null)
    {
        $this->boostTypes = BoostType::all();
        if ($dataInputId) {
            $dataInput = DataInput::findOrFail($dataInputId);
            $this->dataInputId = $dataInput->id;
            $this->customer_name = $dataInput->customer_name;
            $this->page_name = $dataInput->page_name;
            $this->phone = $dataInput->phone;
            $this->boost_type_id = $dataInput->boost_type_id;
            $this->start_date = $dataInput->start_date ? $dataInput->start_date->format('Y-m-d') : null;
            $this->amount = $dataInput->amount;
            $this->mm_kyat = $dataInput->mm_kyat;
            $this->total_amount = $dataInput->total_amount;
            $this->status = $dataInput->status->value;
        } else {
            $this->status = BoostStatus::Charge->value;
        }
    }

    public function save()
    {
        $this->validate();
        $dataInputData = [
            'user_id' => Auth::id(),
            'customer_name' => $this->customer_name,
            'page_name' => $this->page_name,
            'phone' => $this->phone,
            'boost_type_id' => $this->boost_type_id,
            'start_date' => $this->start_date,
            'amount' => $this->amount,
            'mm_kyat' => $this->mm_kyat,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
        ];
        if ($this->dataInputId) {
            $dataInput = DataInput::findOrFail($this->dataInputId);
            $dataInput->update($dataInputData);
        } else {
            DataInput::create($dataInputData);
        }
        return redirect()->route('dashboard')->with('success', 'Data input saved successfully!');
    }

    public function updatedMmKyat()
    {
        $this->calculateTotalAmount();
    }

    public function updatedAmount()
    {
        $this->calculateTotalAmount();
    }

    private function calculateTotalAmount()
    {

        $this->total_amount = ((float)$this->mm_kyat) * ((float)$this->amount);
    }

    public function render()
    {
        return view('livewire.data-input-form', [
            'boostTypes' => $this->boostTypes,
        ]);
    }
}
