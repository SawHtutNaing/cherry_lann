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
    public $discount;
    public $total_amount;
    public $status;
    public $dataInputId;
    public $is_remark;
    public $remark;
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
            'discount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:1,2,3',
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
            $this->discount = $dataInput->discount;
            $this->total_amount = $dataInput->total_amount;
            $this->is_remark = $dataInput->is_remark;
            $this->remark = $dataInput->remark;
            $this->status = $dataInput->status->value;

        } else {
            $this->status = BoostStatus::Charge->value;
            $this->amount = 0;
            $this->mm_kyat = 0;
            $this->discount = 0;
            $this->total_amount = 0;
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
            'discount' => $this->discount,
            'total_amount' => $this->total_amount,
            'is_remark' => $this->is_remark,
            'remark' => $this->remark,
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

    public function updatedMmKyat($value)
    {
        $this->mm_kyat = (float) $value;
        $this->calculateTotalAmount();
    }

    public function updatedAmount($value)
    {
        $this->amount = (float) $value;
        $this->calculateTotalAmount();
    }

    public function updatedDiscount($value)
    {
        $this->discount = (float) $value;
        $this->calculateTotalAmount();
    }

    private function calculateTotalAmount()
    {
        $this->total_amount = ((float) $this->mm_kyat * (float) $this->amount) - (float) $this->discount;
    }

    public function render()
    {
        return view('livewire.data-input-form', [
            'boostTypes' => $this->boostTypes,
        ]);
    }
}
