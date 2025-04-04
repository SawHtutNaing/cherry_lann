<?php

namespace App\Livewire;

use App\BoostStatus as AppBoostStatus;
use Livewire\Component;
use App\Models\DataInput;
use App\Enums\BoostStatus;
use App\Models\BoostType;
use Illuminate\Support\Facades\Auth;

class DataInputForm extends Component
{
    public $page_name;
    public $boost_type_id;
    public $start_date;
    public $amount;
    public $mm_kyat;
    public $total_amount;
    public $status;
    public $dataInputId;



    protected function rules()
    {
        return [
            'page_name' => 'required|string|max:255',
            'boost_type_id' => 'required|exists:boost_types,id',
            'start_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'mm_kyat' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:1,2',

        ];
    }

    public function mount($dataInputId = null)
    {


        // $this->boost_status = AppBoostStatus::
        if ($dataInputId) {
            $dataInput = DataInput::findOrFail($dataInputId);
            $this->dataInputId = $dataInput->id;
            $this->page_name = $dataInput->page_name;
            $this->boost_type_id = $dataInput->boost_type_id;
            $this->start_date = $dataInput->start_date;
            $this->amount = $dataInput->amount;
            $this->mm_kyat = $dataInput->mm_kyat;
            $this->total_amount = $dataInput->total_amount;
            $this->status = $dataInput->status->value;
        }
    }

    public function save()
    {
        // dd($this->status);
        $this->validate();

        $dataInputData = [
            'user_id' => Auth::id(),
            'page_name' => $this->page_name,
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


        return redirect()->route('dashboard');
    }


    public function updatedMmKyat(){

       $this->calculateTotalAmount();

    }
    public function updatedAmount(){
       $this->calculateTotalAmount();
    }





    private function calculateTotalAmount()
    {

        $this->total_amount = ((float)$this->mm_kyat) * ((float)$this->amount);

    }




    public function render()
    {


        return view('livewire.data-input-form');
    }
}
