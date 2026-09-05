<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServiceType;
use App\Models\BoostType;
use App\Models\DataInputItem;
use App\Models\ExchangeRateLog;
use App\Models\UserProfitLog;
use App\Models\User;
use App\Models\Visa;

class ProfitReport extends Component
{
    // Filters
    public $serviceTypeIds = []; // now an array for multi-select
    public $startDate;
    public $endDate;
    public $employeeFilter = 'all'; // 'all' | 'individual'
    public $selectedUserId;

    // Data for filter dropdowns
    public $serviceTypes;
    public $users;

    // Report state
    public $hasGenerated = false;
    public $reportGroups = []; // one entry per selected service type
    public $grandTotals = [];  // combined totals across all selected service types

    // Chart data
    public $pieCharts = [];       // one pie (by boost type) per service group
    public $grandTotalChart = null; // one pie (by service group) when multiple groups selected

    // Visa totals for the same date range / employee filter
    public $visaTotal = 0;
    public $visaBreakdown = []; // per-user breakdown, only populated when employeeFilter = 'all'

    // Validation-style blocking errors (missing logs)
    public $missingExchangeRates = [];
    public $missingProfitLogs = [];

    public function mount()
    {
        $this->serviceTypes = ServiceType::orderBy('name')->get();
        $this->users = User::whereIn('role', ['user', 'admin'])->orderBy('name')->get();
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'serviceTypeIds' => 'required|array|min:1',
            'serviceTypeIds.*' => 'exists:service_types,id',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'employeeFilter' => 'required|in:all,individual',
            'selectedUserId' => 'required_if:employeeFilter,individual|nullable|exists:users,id',
        ];
    }

    protected function messages()
    {
        return [
            'serviceTypeIds.required' => 'Please select at least one service group.',
            'serviceTypeIds.min' => 'Please select at least one service group.',
        ];
    }

    public function generateReport()
    {
        $this->validate();

        $this->hasGenerated = false;
        $this->missingExchangeRates = [];
        $this->missingProfitLogs = [];
        $this->reportGroups = [];
        $this->pieCharts = [];
        $this->grandTotalChart = null;
        $this->visaTotal = 0;
        $this->visaBreakdown = [];
        $this->grandTotals = [
            'line_total' => 0,
            'discount' => 0,
            'revenue' => 0,
            'employee_profit' => 0,
            'my_profit' => 0,
        ];

        $selectedServiceTypes = ServiceType::with('boostTypes')
            ->whereIn('id', $this->serviceTypeIds)
            ->orderBy('name')
            ->get();

        $missingExchangeSet = [];
        $missingProfitSet = [];
        $groupsToRender = [];

        foreach ($selectedServiceTypes as $serviceType) {
            $isDollar = $serviceType->type === 'dollar';
            $boostTypeIds = $serviceType->boostTypes->pluck('id')->toArray();

            if (empty($boostTypeIds)) {
                // Nothing to compute for this service group, but still show it as empty.
                $groupsToRender[] = [
                    'service_type_id' => $serviceType->id,
                    'service_type_name' => $serviceType->name,
                    'is_dollar' => $isDollar,
                    'rows' => [],
                    'totals' => [
                        'line_total' => 0,
                        'discount' => 0,
                        'revenue' => 0,
                        'employee_profit' => 0,
                        'my_profit' => 0,
                    ],
                ];
                continue;
            }

            $itemsQuery = DataInputItem::with('dataInput')
                ->whereIn('boost_type_id', $boostTypeIds)
                ->whereBetween('start_date', [$this->startDate, $this->endDate]);

            if ($this->employeeFilter === 'individual' && $this->selectedUserId) {
                $itemsQuery->whereHas('dataInput', function ($q) {
                    $q->where('user_id', $this->selectedUserId);
                });
            }

            $items = $itemsQuery->get();

            $exchangeLogs = $isDollar
                ? ExchangeRateLog::where('service_type_id', $serviceType->id)->get()
                : collect();

            $profitLogs = UserProfitLog::whereIn('boost_type_id', $boostTypeIds)->get();

            // ---- Validation pass ----
            foreach ($items as $item) {
                $userId = optional($item->dataInput)->user_id;

                if ($isDollar) {
                    $rate = $this->matchExchangeRate($exchangeLogs, $item->start_date);
                    if (!$rate) {
                        $key = $serviceType->id . '|' . $item->start_date->format('Y-m-d');
                        $missingExchangeSet[$key] = [
                            'service_type' => $serviceType->name,
                            'date' => $item->start_date->format('Y-m-d'),
                        ];
                    }
                }

                if (!$userId) {
                    continue;
                }

                $log = $this->matchProfitLog($profitLogs, $userId, $item->boost_type_id, $item->start_date);

                // Dollar type: only a FLAT profit log is valid.
                // MMK type: only a PERCENTAGE profit log is valid.
                $isInvalidForDollar = $isDollar && (!$log || $log->type !== 'flat');
                $isInvalidForMmk = !$isDollar && (!$log || $log->type !== 'percentage');

                if ($isInvalidForDollar || $isInvalidForMmk) {
                    $key = $item->boost_type_id . '|' . $userId . '|' . $item->start_date->format('Y-m-d');
                    $missingProfitSet[$key] = [
                        'service_type' => $serviceType->name,
                        'boost_type_id' => $item->boost_type_id,
                        'user_id' => $userId,
                        'date' => $item->start_date->format('Y-m-d'),
                    ];
                }
            }

            // Stash everything needed to build rows later (after full validation pass).
            $groupsToRender[] = [
                'service_type_id' => $serviceType->id,
                'service_type_name' => $serviceType->name,
                'is_dollar' => $isDollar,
                'boost_type_ids' => $boostTypeIds,
                'items' => $items,
                'exchange_logs' => $exchangeLogs,
                'profit_logs' => $profitLogs,
            ];
        }

        if (!empty($missingExchangeSet)) {
            $this->missingExchangeRates = collect($missingExchangeSet)
                ->sortBy(['service_type', 'date'])
                ->values()
                ->toArray();
        }

        if (!empty($missingProfitSet)) {
            $allBoostTypeIds = array_unique(array_column($missingProfitSet, 'boost_type_id'));
            $allUserIds = array_unique(array_column($missingProfitSet, 'user_id'));

            $boostTypeNames = BoostType::whereIn('id', $allBoostTypeIds)->pluck('name', 'id');
            $userNames = User::whereIn('id', $allUserIds)->pluck('name', 'id');

            $this->missingProfitLogs = collect($missingProfitSet)
                ->map(function ($row) use ($boostTypeNames, $userNames) {
                    return [
                        'service_type' => $row['service_type'],
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

        // ---- Aggregation pass: build rows per service group ----
        foreach ($groupsToRender as $group) {
            // Already-finished empty group (no boost types) — push as is.
            if (!isset($group['items'])) {
                $this->reportGroups[] = $group;
                continue;
            }

            $isDollar = $group['is_dollar'];
            $items = $group['items'];
            $exchangeLogs = $group['exchange_logs'];
            $profitLogs = $group['profit_logs'];
            $boostTypeIds = $group['boost_type_ids'];

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

                    // Discount is tracked for both types (display only — line_total already nets it out).
                    $discount += (float) $item->discount;

                    if ($isDollar) {
                        $rate = $this->matchExchangeRate($exchangeLogs, $item->start_date);
                        $revenue += (float) $item->amount * (float) $rate->amount;
                    }

                    $log = $this->matchProfitLog($profitLogs, $userId, $item->boost_type_id, $item->start_date);
                    if ($log) {
                        if ($isDollar && $log->type === 'flat') {
                            // Dollar type: flat only.
                            $employeeProfit += (float) $item->amount * (float) $log->amount;
                        } elseif (!$isDollar && $log->type === 'percentage') {
                            // MMK type: percentage only.
                            $employeeProfit += (float) $item->line_total * ((float) $log->amount / 100);
                        }
                    }
                }

                // Unified formula for both dollar & mmk types:
                // line_total already reflects discount deducted at input time,
                // so my_profit = line_total - employee_profit (revenue/discount shown for reference only).
                $myProfit = $lineTotal - $employeeProfit;

                $rows[] = [
                    'boost_type_id' => $boostType->id,
                    'boost_type_name' => $boostType->name,
                    'line_total' => $lineTotal,
                    'discount' => $discount,
                    'revenue' => $revenue,
                    'employee_profit' => $employeeProfit,
                    'my_profit' => $myProfit,
                ];

                $totals['line_total'] += $lineTotal;
                $totals['discount'] += $discount;
                $totals['revenue'] += $revenue;
                $totals['employee_profit'] += $employeeProfit;
                $totals['my_profit'] += $myProfit;
            }

            $this->reportGroups[] = [
                'service_type_id' => $group['service_type_id'],
                'service_type_name' => $group['service_type_name'],
                'is_dollar' => $isDollar,
                'rows' => $rows,
                'totals' => $totals,
            ];

            $this->grandTotals['line_total'] += $totals['line_total'];
            $this->grandTotals['discount'] += $totals['discount'];
            $this->grandTotals['revenue'] += $totals['revenue'];
            $this->grandTotals['employee_profit'] += $totals['employee_profit'];
            $this->grandTotals['my_profit'] += $totals['my_profit'];
        }

        // ---- Visa totals for the same date range ----
        $visaQuery = Visa::whereBetween('date', [$this->startDate, $this->endDate]);

        if ($this->employeeFilter === 'individual' && $this->selectedUserId) {
            $this->visaTotal = (float) $visaQuery->where('user_id', $this->selectedUserId)->sum('amount');
            $this->visaBreakdown = [];
        } else {
            $visas = $visaQuery->with('user')->get();
            $this->visaTotal = (float) $visas->sum('amount');

            $this->visaBreakdown = $visas->groupBy('user_id')
                ->map(function ($group) {
                    $user = optional($group->first()->user);
                    return [
                        'user_id' => $user->id ?? null,
                        'user_name' => $user->name ?? 'N/A',
                        'total' => (float) $group->sum('amount'),
                    ];
                })
                ->sortBy('user_name')
                ->values()
                ->toArray();
        }

        // ---- Build pie chart data ----
        foreach ($this->reportGroups as $group) {
            if (!empty($group['rows'])) {
                $this->pieCharts[] = [
                    'id' => $group['service_type_id'],
                    'title' => $group['service_type_name'],
                    'labels' => array_column($group['rows'], 'boost_type_name'),
                    'data' => array_map(fn ($r) => round((float) $r['my_profit'], 2), $group['rows']),
                ];
            }
        }

        if (count($this->reportGroups) > 1) {
            $groupsWithData = array_filter($this->reportGroups, fn ($g) => !empty($g['rows']));

            if (!empty($groupsWithData)) {
                $this->grandTotalChart = [
                    'labels' => array_map(fn ($g) => $g['service_type_name'], $groupsWithData),
                    'data' => array_map(fn ($g) => round((float) $g['totals']['my_profit'], 2), $groupsWithData),
                ];
            }
        }

        $this->hasGenerated = true;

        $this->dispatch('report-charts-updated', pieCharts: $this->pieCharts, grandTotalChart: $this->grandTotalChart);
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
