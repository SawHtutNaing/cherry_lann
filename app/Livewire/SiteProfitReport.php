<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServiceType;
use App\Models\BoostType;
use App\Models\DataInputItem;
use App\Models\ExchangeRateLog;
use App\Models\UserProfitLog;
use App\Models\Expense;
use App\Models\ExpenseCategory;

class SiteProfitReport extends Component
{
    // Filters
    public $startDate;
    public $endDate;

    // State
    public $hasGenerated = false;

    // Blocking validation errors
    public $missingExchangeRates = [];
    public $missingProfitLogs = [];

    // Report output
    public $serviceTypeGroups = [];
    public $grandProfitTotal = 0;

    public $expenseRows = [];
    public $expenseGrandTotal = 0;

    public $netProfit = 0;

    public function mount()
    {
        $this->startDate = '2027-01-01';
        $this->endDate = '2029-01-01';
    }

    protected function rules()
    {
        return [
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ];
    }

    public function generateReport()
    {
        $this->validate();

        $this->hasGenerated = false;
        $this->missingExchangeRates = [];
        $this->missingProfitLogs = [];
        $this->serviceTypeGroups = [];
        $this->grandProfitTotal = 0;
        $this->expenseRows = [];
        $this->expenseGrandTotal = 0;
        $this->netProfit = 0;

        // Sorted by sort_no now instead of name
        $serviceTypes = ServiceType::with('boostTypes')->orderBy('sort_no')->get();

        $allBoostTypeIds = $serviceTypes->pluck('boostTypes')->flatten()->pluck('id')->unique()->values()->toArray();

        if (empty($allBoostTypeIds)) {
            $this->hasGenerated = true;
            return;
        }

        // Pull all items across every boost type in one query, keyed by boost_type_id
        $items = DataInputItem::with('dataInput')
            ->whereIn('boost_type_id', $allBoostTypeIds)
            ->whereBetween('start_date', [$this->startDate, $this->endDate])
            ->get()
            ->groupBy('boost_type_id');

        $dollarServiceTypeIds = $serviceTypes->where('type', 'dollar')->pluck('id')->toArray();

        $exchangeLogsByService = ExchangeRateLog::whereIn('service_type_id', $dollarServiceTypeIds)
            ->get()
            ->groupBy('service_type_id');

        $profitLogs = UserProfitLog::whereIn('boost_type_id', $allBoostTypeIds)->get();

        // ---- Validation pass across every service type / boost type ----
        $missingExchangeSet = [];
        $missingProfitSet = [];

        foreach ($serviceTypes as $serviceType) {
            $isDollar = $serviceType->type === 'dollar';
            $exchangeLogs = $isDollar
                ? $exchangeLogsByService->get($serviceType->id, collect())
                : collect();

            foreach ($serviceType->boostTypes as $boostType) {
                $boostItems = $items->get($boostType->id, collect());

                foreach ($boostItems as $item) {
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

                    $log = $this->matchProfitLog($profitLogs, $userId, $item->boost_type_id, $item->start_date);
                    if (!$log && $userId) {
                        $key = $boostType->id . '|' . $userId . '|' . $item->start_date->format('Y-m-d');
                        $missingProfitSet[$key] = [
                            'boost_type_id' => $boostType->id,
                            'user_id' => $userId,
                            'date' => $item->start_date->format('Y-m-d'),
                        ];
                    }
                }
            }
        }

        if (!empty($missingExchangeSet)) {
            $this->missingExchangeRates = collect($missingExchangeSet)
                ->sortBy(fn ($row) => $row['service_type'] . $row['date'])
                ->values()
                ->toArray();
        }

        if (!empty($missingProfitSet)) {
            $boostTypeNames = BoostType::whereIn('id', $allBoostTypeIds)->pluck('name', 'id');
            $userIds = array_unique(array_column($missingProfitSet, 'user_id'));
            $userNames = \App\Models\User::whereIn('id', $userIds)->pluck('name', 'id');

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
            $this->hasGenerated = false;
            return;
        }

        // ---- Aggregation pass: build groups per service type, rows per boost type ----
        $groups = [];
        $grandProfitTotal = 0;

        foreach ($serviceTypes as $serviceType) {
            $isDollar = $serviceType->type === 'dollar';
            $exchangeLogs = $isDollar
                ? $exchangeLogsByService->get($serviceType->id, collect())
                : collect();

            if ($serviceType->boostTypes->isEmpty()) {
                continue;
            }

            $rows = [];
            $subtotals = [
                'line_total' => 0,
                'discount' => 0,
                'discount_mmk' => 0,
                'revenue' => 0,
                'employee_profit' => 0,
                'my_profit' => 0,
            ];

            foreach ($serviceType->boostTypes as $boostType) {
                $boostItems = $items->get($boostType->id, collect());

                $lineTotal = 0;
                $discount = 0;
                $discountMmk = 0;
                $revenue = 0;
                $employeeProfit = 0;

                foreach ($boostItems as $item) {
                    $userId = optional($item->dataInput)->user_id;
                    $lineTotal += (float) $item->line_total;

                    if ($isDollar) {
                        $discount += (float) $item->discount;
                        $rate = $this->matchExchangeRate($exchangeLogs, $item->start_date);
                        $revenue += (float) $item->amount * (float) $rate->amount;
                        $discountMmk += (float) $item->discount * (float) $rate->amount;
                    }

                    $log = $this->matchProfitLog($profitLogs, $userId, $item->boost_type_id, $item->start_date);
                    if ($log) {
                        if ($log->type === 'flat') {
                            $employeeProfit += (float) $item->amount * (float) $log->amount;
                        } else {
                            $employeeProfit += (float) $item->line_total * ((float) $log->amount / 100);
                        }
                    }
                }

                $myProfit = $isDollar
                    ? ($revenue - $employeeProfit - $discount)
                    : ($lineTotal - $employeeProfit);

                $rows[] = [
                    'boost_type_name' => $boostType->name,
                    'line_total' => $lineTotal,
                    'discount' => $discount,
                    'discount_mmk' => $discountMmk,
                    'revenue' => $revenue,
                    'employee_profit' => $employeeProfit,
                    'my_profit' => $myProfit,
                ];

                $subtotals['line_total'] += $lineTotal;
                $subtotals['discount'] += $discount;
                $subtotals['discount_mmk'] += $discountMmk;
                $subtotals['revenue'] += $revenue;
                $subtotals['employee_profit'] += $employeeProfit;
                $subtotals['my_profit'] += $myProfit;
            }

            $groups[] = [
                'service_type_id' => $serviceType->id,
                'service_type_name' => $serviceType->name,
                'type' => $serviceType->type,
                'rows' => $rows,
                'subtotals' => $subtotals,
            ];

            $grandProfitTotal += $subtotals['my_profit'];
        }

        $this->serviceTypeGroups = $groups;
        $this->grandProfitTotal = $grandProfitTotal;

        // ---- Expenses within the date range, grouped by category ----
        $expenses = Expense::with('expenseCategory')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->get()
            ->groupBy('expense_category_id');

        $expenseRows = [];
        $expenseGrandTotal = 0;

        foreach ($expenses as $categoryId => $categoryExpenses) {
            $categoryName = optional($categoryExpenses->first()->expenseCategory)->name ?? 'Uncategorized';
            $categoryTotal = (float) $categoryExpenses->sum('amount');

            $expenseRows[] = [
                'category' => $categoryName,
                'total' => $categoryTotal,
            ];

            $expenseGrandTotal += $categoryTotal;
        }

        // Sorted descending by amount (largest to smallest)
        $this->expenseRows = collect($expenseRows)->sortByDesc('total')->values()->toArray();
        $this->expenseGrandTotal = $expenseGrandTotal;

        $this->netProfit = $this->grandProfitTotal - $this->expenseGrandTotal;

        $this->hasGenerated = true;
    }

    protected function matchExchangeRate($exchangeLogs, $date)
    {
        return $exchangeLogs->first(function ($log) use ($date) {
            return $log->start_date <= $date && (is_null($log->end_date) || $log->end_date >= $date);
        });
    }

    protected function matchProfitLog($profitLogs, $userId, $boostTypeId, $date)
    {
        return $profitLogs->first(function ($log) use ($userId, $boostTypeId, $date) {
            return (int) $log->user_id === (int) $userId
                && (int) $log->boost_type_id === (int) $boostTypeId
                && $log->from_date <= $date
                && (is_null($log->to_date) || $log->to_date >= $date);
        });
    }

    /**
     * Export the currently generated report as a CSV file
     * (opens natively in Excel).
     */
    public function exportExcel()
    {
        if (!$this->hasGenerated) {
            return;
        }

        $filename = 'site-profit-report_' . $this->startDate . '_to_' . $this->endDate . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel renders Myanmar/unicode text correctly
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Site Profit Report']);
            fputcsv($handle, ['Period', $this->startDate . ' to ' . $this->endDate]);
            fputcsv($handle, []);

            foreach ($this->serviceTypeGroups as $group) {
                fputcsv($handle, [$group['service_type_name'] . ' (' . $group['type'] . ')']);
                fputcsv($handle, ['No', 'Boost Type', 'Line Total', 'Discount', 'Discount (MMK)', 'Revenue', 'E_Profit', 'M_Profit']);

                foreach ($group['rows'] as $i => $row) {
                    $isDollar = $group['type'] === 'dollar';
                    fputcsv($handle, [
                        $i + 1,
                        $row['boost_type_name'],
                        number_format($row['line_total'], 2, '.', ''),
                        $isDollar ? number_format($row['discount'], 2, '.', '') : '-',
                        $isDollar ? number_format($row['discount_mmk'], 2, '.', '') : '-',
                        $isDollar ? number_format($row['revenue'], 2, '.', '') : '-',
                        number_format($row['employee_profit'], 2, '.', ''),
                        number_format($row['my_profit'], 2, '.', ''),
                    ]);
                }

                fputcsv($handle, [
                    'Subtotal',
                    '',
                    number_format($group['subtotals']['line_total'], 2, '.', ''),
                    $group['type'] === 'dollar' ? number_format($group['subtotals']['discount'], 2, '.', '') : '-',
                    $group['type'] === 'dollar' ? number_format($group['subtotals']['discount_mmk'], 2, '.', '') : '-',
                    $group['type'] === 'dollar' ? number_format($group['subtotals']['revenue'], 2, '.', '') : '-',
                    number_format($group['subtotals']['employee_profit'], 2, '.', ''),
                    number_format($group['subtotals']['my_profit'], 2, '.', ''),
                ]);
                fputcsv($handle, []);
            }

            fputcsv($handle, ['Before Expense', number_format($this->grandProfitTotal, 2, '.', '')]);
            fputcsv($handle, []);

            fputcsv($handle, ['Expenses']);
            fputcsv($handle, ['Category', 'Total']);
            foreach ($this->expenseRows as $expense) {
                fputcsv($handle, [$expense['category'], number_format($expense['total'], 2, '.', '')]);
            }
            fputcsv($handle, ['Total Expense', number_format($this->expenseGrandTotal, 2, '.', '')]);
            fputcsv($handle, []);

            fputcsv($handle, ['Summary Net Profit', number_format($this->netProfit, 2, '.', '')]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        return view('livewire.site-profit-report');
    }
}
