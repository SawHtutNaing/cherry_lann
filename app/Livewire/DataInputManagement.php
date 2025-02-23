<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DataInput;

class DataInputManagement extends Component
{
    public $dataInputs;
    public $startDate, $endDate;


    protected $listeners = [
        'dataInputUpdated' => '$refresh',
    ];

    public function mount()
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');



        $query = DataInput::query();
        $query->whereBetween('start_date', [$this->startDate, $this->endDate]);
        $this->dataInputs = $query->get();
    }



    public function delete($id)
    {
        DataInput::find($id)->delete();
        $query = DataInput::query();
        $query->whereBetween('start_date', [$this->startDate, $this->endDate]);
        $this->dataInputs = $query->get();
        $this->render();
    }
    public function filterData()
    {
        $query = DataInput::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('start_date', [$this->startDate, $this->endDate]);
        }

        $this->dataInputs = $query->get();
    }
    public function render()
    {


        return view(
            'livewire.data-input-management',
            [
                'dataInputs' => $this->dataInputs
            ]
        );
    }
}
