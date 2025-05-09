<?php

namespace App\Livewire;

use App\Models\BoostType;
use App\Models\DataInput;
use App\Models\User;
use Livewire\Component;
use App\Exports\DataExport;
use Maatwebsite\Excel\Facades\Excel;

class Report extends Component
{

    public $dataInputs;
    public $isExport = false;
    public $startDate, $endDate;
    public $servicesBys;
    public $boostTypes;
    public $boosttype;
    public $charges;
    public $refund;
    public $users;
    public $status_at;
    public $service_by;
    public $pending_total;


    public function mount()
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->servicesBys = User::all();
        $this->service_by = auth()->id();
        $this->boostTypes = BoostType::all();
        $query = DataInput::query();
        $query->whereBetween('start_date', [$this->startDate, $this->endDate]);
        $query->when($this->service_by , fn($q)=> $q->where('user_id' , $this->service_by) );
        $this->dataInputs = $query->get();

    }

    public function upddateBudget()
    {
        $this->charges = $this->dataInputs->where('status', 1)->sum('amount');
        $this->refund = $this->dataInputs->where('status', 2)->sum('amount');
        $this->pending_total = $this->dataInputs->where('status', 3)->sum('amount');
    }

    public function filterData()
    {
        $query = DataInput::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('start_date', [$this->startDate, $this->endDate]);
        }
        if ($this->service_by) {
            $query->where('user_id', $this->service_by);
        }
        if ($this->boosttype) {
            $query->where('boost_type_id', $this->boosttype);
        }

        if ($this->status_at) {
            $query->where('status', $this->status_at);
        }

        $this->dataInputs = $query->get();
    }


    public function reprotExcel()
    {

        return Excel::download(new DataExport($this->dataInputs, $this->charges, $this->refund), 'cherry_lann.xlsx');
    }
    public function render()
    {
        $this->upddateBudget();
        return view('livewire.report');
    }
}
