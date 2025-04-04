<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DataInput;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\BoostStatus;
use Mpdf\Mpdf;

use Illuminate\Support\Facades\Response;

class DataInputManagement extends Component
{
    public $dataInputs;
    public $startDate, $endDate;


    protected $listeners = [
        'dataInputUpdated' => '$refresh',
    ];



    public function export($id)
    {
        $boost = DataInput::find($id);
        // Render the HTML using Blade
        $html = View::make('livewire.img-export', [
            'page_name' => $boost->page_name ?? 'Sample Page',
            'boost_type_id' => $boost->boostType->name ,
            'start_date' => $boost->start_date ? Carbon::parse($boost->start_date)->format('Y-m-d') : now()->format('Y-m-d'),
            'status' => is_int($boost->status) ? BoostStatus::from($boost->status)->label() : BoostStatus::Charge->label(),
            'amount' => $boost->amount ,
            'generated_date' => now()->format('d-m-Y'),
            'mm_kyat' => $boost->mm_kyat ,
            'total_amount' => $boost->total_amount,
        ])->render();


        $mpdf = new Mpdf([
            'fontDir' => [storage_path('fonts')],
            'fontdata' => [
                'myanmar' => [
                    'R' => 'padauk.ttf',  // Font file path
                    'B' => 'padauk.ttf',  // Font file path for bold
                ]
                ],
            'format' => 'A4',
            'mode' => 'utf-8',
        ]);




        // $mpdf->SetFont('tharlon');
        $mpdf->WriteHTML($html);
        return response()->streamDownload(function() use ($mpdf) {
            return $mpdf->Output('voucher.pdf', 'D');
        }, 'export_img.pdf');
    }
    public function mount()
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');



        $query = DataInput::query()
        ->where('user_id', auth()->id())
        ;
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
        $query = DataInput::query()
        ->where('user_id', auth()->id())
        ;

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
