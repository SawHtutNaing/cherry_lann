<?php

namespace App\Livewire;

use App\Models\BoostType;
use App\Models\DataInput;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\DataExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Throwable;

class Report extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $dataInputs;

    /**
     * Always false for the live Livewire page. The blade view is shared
     * with a separate export/print route which sets this to true via
     * view()->with('isExport', true) — never toggle it inside this
     * component.
     */
    public $isExport = false;

    public $startDate, $endDate;
    public $servicesBys;
    public $boostTypes;
    public $boosttype = [];
    public $charges = 0;
    public $refund = 0;
    public $users;
    public $status_at;
    public $service_by;
    public $pending_total = 0;
    public $totalCount = 0;
    public $cus_name_search;
    public function mount()
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->servicesBys = User::all();
        $this->service_by = auth()->id();
        $this->boostTypes = BoostType::all();
    }

    /**
     * Single source of truth for the current filters.
     * Reused for the on-screen page, the totals, and the export
     * so all three always agree with each other.
     */
    private function baseQuery()
    {
        return DataInput::query()
            ->when($this->startDate && $this->endDate, function ($q) {
                $q->whereBetween('start_date', [$this->startDate, $this->endDate]);
            })
            ->when($this->service_by, function ($q) {
                $q->where('user_id', $this->service_by);
            })
            ->when(!empty($this->boosttype), function ($q) {
                $q->whereIn('boost_type_id', $this->boosttype);
            })
            ->when($this->cus_name_search, function ($q) {
                $q->where('customer_name', 'like', '%' . $this->cus_name_search . '%');
            })
            ->when($this->status_at, function ($q) {
                $q->where('status', $this->status_at);
            });
    }

    /**
     * Compute Charge / Refund / Pending / Total in SQL instead of
     * pulling every row into PHP and summing with Collection methods.
     */
    private function updateAggregates(): void
    {
        $query = $this->baseQuery();

        $this->totalCount = (clone $query)->count();

        $agg = (clone $query)->selectRaw('
            COALESCE(SUM(CASE WHEN status = 1 THEN amount END), 0) as charges,
            COALESCE(SUM(CASE WHEN status = 2 THEN amount END), 0) as refund,
            COALESCE(SUM(CASE WHEN status = 3 THEN amount END), 0) as pending
        ')->first();

        $this->charges = (float) $agg->charges;
        $this->refund = (float) $agg->refund;
        $this->pending_total = (float) $agg->pending;
    }

    public function filterData()
    {
        $this->resetPage();
    }

    public function updated($property)
    {
        // Whenever a filter field changes, jump back to page 1
        // so the paginator doesn't end up on an out-of-range page.
        if (in_array($property, ['cus_name_search', 'startDate', 'endDate', 'service_by', 'boosttype', 'status_at'])) {
            $this->resetPage();
        }
    }

    public function reprotExcel()
    {
        try {
            // Bounded memory limit instead of unlimited (-1), which can crash the server.
            ini_set('memory_limit', '1024M');
            set_time_limit(300);

            // Fresh, FULL (non-paginated) collection for the export — the
            // on-screen $dataInputs is paginated and must not be reused here.
            $exportData = $this->baseQuery()
                ->with(['user', 'boostType'])
                ->orderByDesc('start_date')
                ->get();

            $fileName = 'exports/cherry_lann_' . now()->format('Ymd_His') . '.xlsx';

            // Store to disk instead of Excel::download(). This avoids Livewire
            // base64-encoding the whole file into its AJAX response (which is
            // what freezes the tab on large exports).
            Excel::store(
                new DataExport($exportData, $this->charges, $this->refund, $this->pending_total),
                $fileName,
                'public'
            );

            $this->dispatch('excel-ready', url: Storage::disk('public')->url($fileName));
        } catch (Throwable $e) {
            report($e);
            $this->dispatch('excel-error', message: 'Export failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $this->updateAggregates();

        $this->dataInputs = $this->baseQuery()
            ->with(['user', 'boostType'])
            ->orderByDesc('start_date')
            ->paginate(25);

        return view('livewire.report');
    }
}
