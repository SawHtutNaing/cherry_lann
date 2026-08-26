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
    public $overall_total = 0;
    public $totalCount = 0;
    public $cus_name_search;
    public $days_count;   // NEW — "Days ≥" filter

    public function mount()
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->servicesBys = User::all();
        $this->service_by = auth()->id();
        $this->boostTypes = BoostType::all();
    }

    private function baseQuery()
    {
        return DataInput::query()
            ->when($this->startDate && $this->endDate, function ($q) {
                $q->whereHas('items', function ($iq) {
                    $iq->whereBetween('start_date', [$this->startDate, $this->endDate]);
                });
            })
            ->when($this->service_by, function ($q) {
                $q->where('user_id', $this->service_by);
            })
            ->when(!empty($this->boosttype), function ($q) {
                $q->whereHas('items', function ($iq) {
                    $iq->whereIn('boost_type_id', $this->boosttype);
                });
            })
            ->when($this->cus_name_search, function ($q) {
                $q->where('customer_name', 'like', '%' . $this->cus_name_search . '%');
            })
            ->when($this->status_at, function ($q) {
                $q->where('status', $this->status_at);
            })
            // NEW — days-since-created filter, same as the dashboard controller
            ->when($this->days_count !== null && $this->days_count !== '', function ($q) {
                $q->whereRaw('DATEDIFF(CURDATE(), created_at) >= ?', [(int) $this->days_count]);
            });
    }

    private function updateAggregates(): void
    {
        $query = $this->baseQuery();

        $this->totalCount = (clone $query)->count();

        $agg = (clone $query)->selectRaw('
            COALESCE(SUM(CASE WHEN status = 1 THEN total_amount END), 0) as charges,
            COALESCE(SUM(CASE WHEN status = 2 THEN total_amount END), 0) as refund,
            COALESCE(SUM(CASE WHEN status = 3 THEN total_amount END), 0) as pending,
            COALESCE(SUM(total_amount), 0) as overall
        ')->first();

        $this->charges = (float) $agg->charges;
        $this->refund = (float) $agg->refund;
        $this->pending_total = (float) $agg->pending;
        $this->overall_total = (float) $agg->overall;
    }

    public function filterData()
    {
        $this->resetPage();
    }

    public function updated($property)
    {
        if (in_array($property, ['cus_name_search', 'startDate', 'endDate', 'service_by', 'boosttype', 'status_at', 'days_count'])) {
            $this->resetPage();
        }
    }

    public function reprotExcel()
    {
        try {
            ini_set('memory_limit', '1024M');
            set_time_limit(300);

            $this->updateAggregates();

            $exportData = $this->baseQuery()
                ->with(['user', 'items.boostType'])
                ->orderByDesc('created_at')
                ->get();

            $fileName = 'exports/cherry_lann_' . now()->format('Ymd_His') . '.xlsx';

            Excel::store(
                new DataExport($exportData, $this->charges, $this->refund, $this->pending_total, $this->overall_total),
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

        $dataInputs = $this->baseQuery()
            ->with(['user', 'items.boostType', 'clientImages', 'serviceImages'])
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('livewire.report', [
            'dataInputs' => $dataInputs,
            'isExport'   => false,
        ]);
    }
}
