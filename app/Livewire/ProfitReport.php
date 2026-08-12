<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServiceType;
use App\Models\BoostType;
use App\Models\DataInputItem;
use App\Models\ExchangeRateLog;
use App\Models\UserProfitLog;
use App\Models\User;

class ProfitReport extends Component
{
    // Filters
    public $serviceTypeId;
    public $startDate;
    public $endDate;
    public $employeeFilter = 'all'; // 'all' | 'individual'
    public $selectedUserId;

    // Data for filter dropdowns
    public $serviceTypes;
    public $users;

    // Report state
    public $hasGenerated = false;
    public $reportRows = [];
    public $reportTotals = [];
    public $serviceType; // the selected ServiceType model (holds ->type)

    // Validation-style blocking errors (missing logs)
    public $missingExchangeRates = [];
    public $missingProfitLogs = [];

    public function mount()
    {
        $this->serviceTypes = ServiceType::orderBy('name')->get();
        $this->users = User::where('role', 'user')->orderBy('name')->get();
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'serviceTypeId' => 'required|exists:service_types,id',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'employeeFilter' => 'required|in:all,individual',
            'selectedUserId' => 'required_if:employeeFilter,individual|nullable|exists:users,id',
        ];
    }

    public function generateReport()
    {
        $this->validate();

        $this->hasGenerated = false;
        $this->missingExchangeRates = [];
        $this->missingProfitLogs = [];
        $this->reportRows = [];
        $this->reportTotals = [];

        $this->serviceType = ServiceType::with('boostTypes')->findOrFail($this->serviceTypeId);
        $isDollar = $this->serviceType->type === 'dollar';

        $boostTypeIds = $this->serviceType->boostTypes->pluck('id')->toArray();

        if (empty($boostTypeIds)) {
            $this->hasGenerated = true; // nothing to show, but not an error
            return;
        }

        // Pull all relevant items in one query
        $itemsQuery = DataInputItem::with('dataInput')
            ->whereIn('boost_type_id', $boostTypeIds)
            ->whereBetween('start_date', [$this->startDate, $this->endDate]);

        if ($this->employeeFilter === 'individual' && $this->selectedUserId) {
            $itemsQuery->whereHas('dataInput', function ($q) {
                $q->where('user_id', $this->selectedUserId);
            });
        }

        $items = $itemsQuery->get();

        // Preload exchange rate logs (dollar only) and profit logs for matching in memory
        $exchangeLogs = $isDollar
            ? ExchangeRateLog::where('service_type_id', $this->serviceTypeId)->get()
            : collect();

        $profitLogs = UserProfitLog::whereIn('boost_type_id', $boostTypeIds)->get();

        // ---- Validation pass: find missing exchange rates / profit logs before computing anything ----
        $missingExchangeSet = [];
        $missingProfitSet = [];

        foreach ($items as $item) {
            $userId = optional($item->dataInput)->user_id;

            if ($isDollar) {
                $rate = $this->matchExchangeRate($exchangeLogs, $item->start_date);
                if (!$rate) {
                    $key = $item->start_date->format('Y-m-d');
                    $missingExchangeSet[$key] = $key;
                }
            }

            $log = $this->matchProfitLog($profitLogs, $userId, $item->boost_type_id, $item->start_date);
            if (!$log && $userId) {
                $key = $item->boost_type_id . '|' . $userId . '|' . $item->start_date->format('Y-m-d');
                $missingProfitSet[$key] = [
                    'boost_type_id' => $item->boost_type_id,
                    'user_id' => $userId,
                    'date' => $item->start_date->format('Y-m-d'),
                ];
            }
        }

        if (!empty($missingExchangeSet)) {
            $this->missingExchangeRates = collect($missingExchangeSet)->sort()->values()->toArray();
        }

        if (!empty($missingProfitSet)) {
            $boostTypeNames = BoostType::whereIn('id', $boostTypeIds)->pluck('name', 'id');
            $userNames = User::whereIn('id', array_column($missingProfitSet, 'user_id'))->pluck('name', 'id');

            $this->missingProfitLogs = collect($missingProfitSet)
                ->map(function ($row) use ($boostTypeNames, $userNames) {
                    return [
                        'boost_type' => $boostTypeNames[$row['boost_type_id']] ?? 'Unknown',
                        'user' => $userNames[$row['user_id']] ?? 'Unknown',
                        'date' => $row['date'],
                    ];
                })
                ->sortBy('date')
                ->values()
                ->toArray();
        }

        if (!empty($this->missingExchangeRates) || !empty($this->missingProfitLogs)) {
            // Stop here — do not render the report table.
            $this->hasGenerated = false;
            return;
        }

        // ---- Aggregation pass: build one row per boost type ----
        $itemsByBoostType = $items->groupBy('boost_type_id');
        $boostTypes = BoostType::whereIn('id', $boostTypeIds)->orderBy('name')->get();

        $rows = [];
        $totals = [
            'line_total' => 0,
            'discount' => 0,
            'revenue' => 0,
            'employee_profit' => 0,
            'my_profit' => 0,
        ];

        foreach ($boostTypes as $boostType) {
            $boostItems = $itemsByBoostType->get($boostType->id, collect());

            $lineTotal = 0;
            $discount = 0;
            $revenue = 0;
            $employeeProfit = 0;

            foreach ($boostItems as $item) {
                $userId = optional($item->dataInput)->user_id;
                $lineTotal += (float) $item->line_total;

                if ($isDollar) {
                    $discount += (float) $item->discount;
                    $rate = $this->matchExchangeRate($exchangeLogs, $item->start_date);
                    $revenue += (float) $item->amount * (float) $rate->amount;
                }

                $log = $this->matchProfitLog($profitLogs, $userId, $item->boost_type_id, $item->start_date);
                if ($log) {
                    if ($log->type === 'flat') {
                        $employeeProfit += (float) $item->amount * (float) $log->amount;
                    } else { // percentage
                        $employeeProfit += (float) $item->line_total * ((float) $log->amount / 100);
                    }
                }
            }

            if ($isDollar) {
                $myProfit = $revenue - $employeeProfit - $discount;
            } else {
                $myProfit = $lineTotal - $employeeProfit;
            }

            $row = [
                'boost_type_id' => $boostType->id,
                'boost_type_name' => $boostType->name,
                'line_total' => $lineTotal,
                'discount' => $discount,
                'revenue' => $revenue,
                'employee_profit' => $employeeProfit,
                'my_profit' => $myProfit,
            ];

            $rows[] = $row;

            $totals['line_total'] += $lineTotal;
            $totals['discount'] += $discount;
            $totals['revenue'] += $revenue;
            $totals['employee_profit'] += $employeeProfit;
            $totals['my_profit'] += $myProfit;
        }

        $this->reportRows = $rows;
        $this->reportTotals = $totals;
        $this->hasGenerated = true;
    }

    /**
     * Find the ExchangeRateLog covering a given date (start_date <= date <= end_date).
     */
    protected function matchExchangeRate($exchangeLogs, $date)
    {
        return $exchangeLogs->first(function ($log) use ($date) {
            return $log->start_date <= $date && (is_null($log->end_date) || $log->end_date >= $date);
        });
    }

    /**
     * Find the UserProfitLog for a given user + boost type covering a given date.
     */
    protected function matchProfitLog($profitLogs, $userId, $boostTypeId, $date)
    {
        return $profitLogs->first(function ($log) use ($userId, $boostTypeId, $date) {
            return (int) $log->user_id === (int) $userId
                && (int) $log->boost_type_id === (int) $boostTypeId
                && $log->from_date <= $date
                && (is_null($log->to_date) || $log->to_date >= $date);
        });
    }

    public function render()
    {
        return view('livewire.profit-report');
    }
}
