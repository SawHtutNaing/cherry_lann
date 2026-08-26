<?php

// app/Livewire/DataInputForm.php
namespace App\Livewire;

use App\BoostStatus;
use Livewire\Component;
use App\Models\DataInput;
use App\Models\BoostType;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;

class DataInputForm extends Component
{
    public $customer_name;
    public $page_name;
    public $phone;
    public $region_id;
    public $status;
    public $is_remark;
    public $remark;
    public $total_amount = 0;
    public $dataInputId;
    public $boostTypes;
    public $regions;
    public array $items = [];

    protected function rules()
    {
        return [
            'customer_name'        => 'nullable|string|max:255',
            'page_name'            => 'required|string|max:255',
            'phone'                => 'nullable|string|max:20',
            'region_id'            => 'nullable|exists:regions,id',
            'status'               => 'required|in:1,2,3,4',
            'items'                => 'required|array|min:1',
            'items.*.boost_type_id'=> 'required|exists:boost_types,id',
            'items.*.start_date'   => 'required|date',
            'items.*.amount'       => 'required|numeric|min:0',
            'items.*.mm_kyat'      => 'required|numeric|min:0',
            'items.*.discount'     => 'required|numeric|min:0',
        ];
    }

    public function mount($dataInputId = null)
    {
        // Ordered enabled-first so disabled ones group naturally at the bottom
        $this->boostTypes = BoostType::orderByDesc('is_active')->orderBy('name')->get();
        $this->regions    = Region::orderByDesc('is_active')->orderBy('name')->get();

        if ($dataInputId) {
            $dataInput = DataInput::with('items')->findOrFail($dataInputId);

            $this->dataInputId   = $dataInput->id;
            $this->customer_name = $dataInput->customer_name;
            $this->page_name     = $dataInput->page_name;
            $this->phone         = $dataInput->phone;
            $this->region_id     = $dataInput->region_id;
            $this->is_remark     = $dataInput->is_remark;
            $this->remark        = $dataInput->remark;
            $this->status        = $dataInput->status->value;
            $this->total_amount  = $dataInput->total_amount;

            $this->items = $dataInput->items->map(fn ($item) => [
                'id'            => $item->id,
                'boost_type_id' => $item->boost_type_id,
                'start_date'    => optional($item->start_date)->format('Y-m-d'),
                'amount'        => $item->amount,
                'mm_kyat'       => $item->mm_kyat,
                'discount'      => $item->discount,
                'line_total'    => $item->line_total,
            ])->toArray();
        } else {
            $this->status       = BoostStatus::Charge->value;
            $this->total_amount = 0;
            $this->addItem();
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'id'            => null,
            'boost_type_id' => '',
            'start_date'    => now()->format('Y-m-d'),
            'amount'        => 0,
            'mm_kyat'       => 0,
            'discount'      => 0,
            'line_total'    => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotalAmount();
    }

    // Catch-all: fires on any items.{i}.{field} change
    public function updated($name, $value)
    {
        if (preg_match('/^items\.(\d+)\.(amount|mm_kyat|discount)$/', $name, $m)) {
            $this->recalculateItem((int) $m[1]);
            $this->calculateTotalAmount();
        }
    }

    private function recalculateItem($index)
    {
        $item = $this->items[$index];
        $this->items[$index]['line_total'] =
            ((float) ($item['mm_kyat'] ?? 0) * (float) ($item['amount'] ?? 0))
            - (float) ($item['discount'] ?? 0);
    }

    private function calculateTotalAmount()
    {
        $this->total_amount = collect($this->items)->sum('line_total');
    }

    public function save()
    {
        $this->validate();
        $this->calculateTotalAmount();

        $dataInputData = [
            'user_id'       => Auth::id(),
            'customer_name' => $this->customer_name,
            'page_name'     => $this->page_name,
            'phone'         => $this->phone,
            'region_id'     => $this->region_id,
            'total_amount'  => $this->total_amount,
            'is_remark'     => $this->is_remark,
            'remark'        => $this->remark,
            'status'        => $this->status,
        ];

        if ($this->dataInputId) {
            $dataInput = DataInput::findOrFail($this->dataInputId);
            $dataInput->update($dataInputData);
        } else {
            $dataInput = DataInput::create($dataInputData);
        }

        $keepIds = collect($this->items)->pluck('id')->filter()->all();
        $dataInput->items()->whereNotIn('id', $keepIds)->delete();

        foreach ($this->items as $item) {
            $payload = [
                'boost_type_id' => $item['boost_type_id'],
                'start_date'    => $item['start_date'],
                'amount'        => $item['amount'],
                'mm_kyat'       => $item['mm_kyat'],
                'discount'      => $item['discount'],
                'line_total'    => $item['line_total'],
            ];

            if (!empty($item['id'])) {
                $dataInput->items()->where('id', $item['id'])->update($payload);
            } else {
                $dataInput->items()->create($payload);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Data input saved successfully!');
    }

    public function render()
    {
        return view('livewire.data-input-form', [
            'boostTypes' => $this->boostTypes,
            'regions'    => $this->regions,
        ]);
    }
}
