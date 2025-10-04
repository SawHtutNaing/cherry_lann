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
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
 use Illuminate\Support\Facades\DB;


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
        try {
            // Fetch the boost data
            $boost = DataInput::with('boostType')->findOrFail($id);

            // Load and convert the logo to base64
            $signPath = public_path('images/sign.jpg');
            $logoPath = config('app.logo_path', public_path('images/logo.jpeg'));
            $logoBase64 = '';
            $signBase64 = '';
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($logoData);
            } else {
                $logoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('images/fallback-logo.jpeg')));
            }

            if (file_exists($signPath)) {
                $signData = file_get_contents($signPath);
                $signBase64 = 'data:image/jpeg;base64,' . base64_encode($signData);
            } else {
                $signBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('images/fallback-logo.jpeg')));
            }

            // Validate calculations
            $subtotal = ($boost->mm_kyat ?? 0) * ($boost->amount ?? 0);
            if (abs($subtotal - ($boost->total_amount ?? 0) + ($boost->discount ?? 0)) > 0.01) {
                // \Log::warning("Total amount mismatch for boost ID {$id}. Subtotal: {$subtotal}, Total: {$boost->total_amount}, Discount: {$boost->discount}");
            }

            // Prepare data for the view
            $data = [
                'id' => $id,
                'customer_name' => $boost->customer_name ?? 'N/A',
                'page_name' => $boost->page_name ?? 'Sample Page',
                'phone' => $boost->phone ?? 'N/A',
                'boost_type_id' => $boost->boostType->name ?? 'N/A',
                'start_date' => $boost->start_date ? Carbon::parse($boost->start_date)->format('Y-m-d') : now()->format('Y-m-d'),
                'amount' => $boost->amount ?? 0,
                'mm_kyat' => $boost->mm_kyat ?? 0,
                'total_amount' => $boost->total_amount ?? 0,
                'discount' => $boost->discount ?? 0,
                'generated_date' => now()->format('d-m-Y'),
                'logo_base64' => $logoBase64,
                'sign_base64' => $signBase64,
            ];

            // Render the PDF using LaravelMpdf
            $pdf = PDF::loadView('livewire.img-export', $data, [
                'fontDir' => [storage_path('fonts')],
                'fontdata' => [
                    'myanmar' => [
                        'R' => 'padauk.ttf',
                        'B' => 'Padauk-Bold.ttf',
                        'EB' => 'ex_bold.ttf'
                    ],
                    'mm_bold' => [
                        'R' => 'Montserrat-Black.ttf',
                        'B' => 'Montserrat-Black.ttf',
                        'EB' => 'Montserrat-Black.ttf'
                    ],
                ],
                'format' => 'A4',
                'mode' => 'utf-8',
                'default_font' => 'myanmar',
            ]);

            // Stream the PDF with a dynamic filename
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, "voucher_{$id}.pdf");

        } catch (\Exception $e) {
            \Log::error('Failed to export voucher: ' . $e->getMessage());
            session()->flash('error', 'Unable to generate voucher. Please try again later.');
            return redirect()->back();
        }
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
    public $check_remark;
    public $cus_name_search;


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

        if($this->cus_name_search){
            $query->where('customer_name', 'like', '%' . $this->cus_name_search . '%');

        }

        if($this->status_at){
            $query->where('status', $this->status_at);

        }

        if($this->check_remark){
            $query->where('is_remark',1);

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

    public function copy($id){
        $product = DataInput::find($id);

$newProduct = $product->replicate();
$newProduct->save();
$this->filterData();

    }


public function exportDatabase()
{
    $ds = DIRECTORY_SEPARATOR;
    $path = storage_path('app' . $ds . 'backups');

    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }

    $filename = "backup-" . Carbon::now()->format('Y-m-d-H-i-s') . ".sql";
    $fullPath = $path . $ds . $filename;

    $tables = DB::select('SHOW TABLES');
    $dbName = config('database.connections.mysql.database');
    $key = 'Tables_in_' . $dbName;
    $sqlScript = '';

    foreach ($tables as $table) {
        $tableName = $table->$key;

        // Create table statement
        $create = DB::select("SHOW CREATE TABLE `$tableName`")[0]->{'Create Table'};
        $sqlScript .= "DROP TABLE IF EXISTS `$tableName`;\n$create;\n\n";

        // Insert data
        $rows = DB::table($tableName)->get();
        foreach ($rows as $row) {
            $values = array_map(fn($v) => DB::getPdo()->quote($v), (array)$row);
            $sqlScript .= "INSERT INTO `$tableName` VALUES (" . implode(',', $values) . ");\n";
        }

        $sqlScript .= "\n\n";
    }

    file_put_contents($fullPath, $sqlScript);

    session()->flash('success', 'Database exported successfully!');

    return response()->download($fullPath)->deleteFileAfterSend(true);
}

}
