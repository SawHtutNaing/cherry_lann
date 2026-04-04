<?php

namespace App\Http\Controllers;

use App\Models\DataInput;
use App\Models\BoostType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class DataInputManagementController extends Controller
{
    public function index(Request $request)
    {
        $boostTypes = BoostType::all();

        $startDate   = $request->input('start_date',   now()->subDays(30)->format('Y-m-d'));
        $endDate     = $request->input('end_date',     now()->format('Y-m-d'));
        $boosttype   = $request->input('boosttype');
        $statusAt    = $request->input('status_at');
        $cusName     = $request->input('cus_name_search');
        $checkRemark = $request->input('check_remark');

        $query = DataInput::query()
            ->where('user_id', auth()->id())
            ->with('boostType')
            ->orderBy('created_at', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate]);
        }
        if ($boosttype) {
            $query->where('boost_type_id', $boosttype);
        }
        if ($cusName) {
            $query->where('customer_name', 'like', '%' . $cusName . '%');
        }
        if ($statusAt) {
            $query->where('status', $statusAt);
        }
        if ($checkRemark) {
            $query->where('is_remark', 1);
        }

        $dataInputs = $query->get();

        return view('data-inputs.index', compact(
            'dataInputs', 'boostTypes',
            'startDate', 'endDate', 'boosttype', 'statusAt', 'cusName', 'checkRemark'
        ));
    }

    public function delete($id)
    {
        DataInput::where('user_id', auth()->id())->findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Data Input deleted successfully!')
            ->withInput();
    }

    public function copy($id)
    {
        $original = DataInput::where('user_id', auth()->id())->findOrFail($id);
        $copy = $original->replicate();
        $copy->save();

        return redirect()->back()
            ->with('success', 'Record copied successfully!')
            ->withInput();
    }

    public function export($id)
    {
        try {
            $boost = DataInput::with('boostType')->findOrFail($id);

            $logoPath = config('app.logo_path', public_path('images/logo.jpeg'));
            $signPath = public_path('images/sign.jpg');

            $logoBase64 = file_exists($logoPath)
                ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
                : 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('images/fallback-logo.jpeg')));

            $signBase64 = file_exists($signPath)
                ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($signPath))
                : 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('images/fallback-logo.jpeg')));

            $data = [
                'id'            => $id,
                'customer_name' => $boost->customer_name ?? 'N/A',
                'page_name'     => $boost->page_name ?? 'Sample Page',
                'phone'         => $boost->phone ?? 'N/A',
                'boost_type_id' => $boost->boostType->name ?? 'N/A',
                'start_date'    => $boost->start_date ? Carbon::parse($boost->start_date)->format('Y-m-d') : now()->format('Y-m-d'),
                'amount'        => $boost->amount ?? 0,
                'mm_kyat'       => $boost->mm_kyat ?? 0,
                'total_amount'  => $boost->total_amount ?? 0,
                'discount'      => $boost->discount ?? 0,
                'generated_date'=> now()->format('d-m-Y'),
                'logo_base64'   => $logoBase64,
                'sign_base64'   => $signBase64,
            ];

            $pdf = PDF::loadView('livewire.img-export', $data, [
                'fontDir' => [storage_path('fonts')],
                'fontdata' => [
                    'myanmar' => [
                        'R'  => 'padauk.ttf',
                        'B'  => 'Padauk-Bold.ttf',
                        'EB' => 'ex_bold.ttf',
                    ],
                    'mm_bold' => [
                        'R'  => 'Montserrat-Black.ttf',
                        'B'  => 'Montserrat-Black.ttf',
                        'EB' => 'Montserrat-Black.ttf',
                    ],
                ],
                'format'       => 'A4',
                'mode'         => 'utf-8',
                'default_font' => 'myanmar',
            ]);

            $pdfContent = $pdf->output();

            return response($pdfContent, 200, [
                'Content-Type'           => 'application/pdf',
                'Content-Disposition'    => 'attachment; filename="voucher_' . $id . '.pdf"',
                'Content-Length'         => strlen($pdfContent),
                'Cache-Control'          => 'no-cache, no-store, must-revalidate',
                'Pragma'                 => 'no-cache',
                'Expires'                => '0',
                'X-Content-Type-Options' => 'nosniff',
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to export voucher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to generate voucher. Please try again later.');
        }
    }

    public function exportDatabase()
    {
        $ds   = DIRECTORY_SEPARATOR;
        $path = storage_path('app' . $ds . 'backups');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
        $fullPath = $path . $ds . $filename;

        $tables    = DB::select('SHOW TABLES');
        $dbName    = config('database.connections.mysql.database');
        $key       = 'Tables_in_' . $dbName;
        $sqlScript = '';

        foreach ($tables as $table) {
            $tableName = $table->$key;
            $create    = DB::select("SHOW CREATE TABLE `$tableName`")[0]->{'Create Table'};
            $sqlScript .= "DROP TABLE IF EXISTS `$tableName`;\n$create;\n\n";

            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $values     = array_map(fn($v) => DB::getPdo()->quote($v), (array) $row);
                $sqlScript .= "INSERT INTO `$tableName` VALUES (" . implode(',', $values) . ");\n";
            }

            $sqlScript .= "\n\n";
        }

        file_put_contents($fullPath, $sqlScript);

        return response()->download($fullPath)->deleteFileAfterSend(true);
    }
}