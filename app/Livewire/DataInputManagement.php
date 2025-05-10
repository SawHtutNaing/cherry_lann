<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DataInput;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
// use Intervention\Image\Facades\Image MissinGrok\Image;
use Illuminate\Support\Facades\Storage;
use App\BoostStatus;
use App\Models\BoostType;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Response;

class DataInputManagement extends Component
{
    public $dataInputs;
    public $startDate, $endDate;
    public $boostTypes;

    protected $listeners = [
        'dataInputUpdated' => '$refresh',
    ];

    public function export($id)
    {
        $boost = DataInput::with('boostType')->findOrFail($id);
        // Render the HTML using Blade
        $html = View::make('livewire.img-export', [
            'customer_name' => $boost->customer_name ?? 'N/A',
            'page_name' => $boost->page_name ?? 'Sample Page',
            'phone' => $boost->phone ?? 'N/A',
            'boost_type_id' => $boost->boostType->name ?? 'N/A',
            // 'boost_type_iddd' => $boost->boost_type_id,
            'start_date' => $boost->start_date ? Carbon::parse($boost->start_date)->format('Y-m-d') : now()->format('Y-m-d'),
            'status' => $boost->status, // Pass the raw enum value
            'amount' => $boost->amount ?? 0,
            'mm_kyat' => $boost->mm_kyat ?? 0,
            'total_amount' => $boost->total_amount ?? 0,
            'created_at' => Carbon::parse($boost->created_at)->format('d/m/y H:i'),
            'updated_at' => Carbon::parse($boost->updated_at)->format('d/m/y H:i'),
            'generated_date' => now()->format('d-m-Y'),
        ])->render();

        $mpdf = new Mpdf([
            'fontDir' => [storage_path('fonts')],
            'fontdata' => [
                'myanmar' => [
                    'R' => 'padauk.ttf',
                    'B' => 'padauk.ttf',
                ],
            ],
            'format' => 'A4',
            'mode' => 'utf-8',
            'default_font' => 'myanmar',
        ]);

        $mpdf->WriteHTML($html);
        return response()->streamDownload(function () use ($mpdf) {
            $mpdf->Output('voucher.pdf', 'D');
        }, 'export_voucher.pdf');
    }

    public function mount()
    {
        $this->boostTypes = BoostType::all();
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->filterData();
    }

    public function delete($id)
    {
        DataInput::findOrFail($id)->delete();
        $this->filterData();
        session()->flash('success', 'Data Input deleted successfully!');
    }

    public $status_at;
    public $boosttype;


    public function filterData()
    {
        $query = DataInput::query()
    ->where('user_id', auth()->id())
    ->with('boostType')
    ->orderBy('created_at', 'desc');


        if ($this->startDate && $this->endDate) {
            $query->whereBetween('start_date', [$this->startDate, $this->endDate]);
        }
        if($this->boosttype){
            $query->where('boost_type_id', $this->boosttype);

        }

        if($this->status_at){
            $query->where('status', $this->status_at);

        }


        $this->dataInputs = $query->get();

    }

    public function render()
    {
        return view(
            'livewire.data-input-management',
            [
                'dataInputs' => $this->dataInputs,
            ]
        );
    }
}
