<div class="container px-3 mx-auto mt-6 sm:mt-8 sm:px-4">
    <h1 class="mb-4 text-lg font-semibold sm:mb-6 sm:text-2xl">Profit Report</h1>

    {{-- Filter form --}}
    <div class="p-3 mb-4 bg-white border border-gray-200 rounded shadow sm:p-4 sm:mb-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block mb-1 text-sm font-medium text-gray-700">Service Group</label>
                <div class="grid grid-cols-1 gap-2 p-3 border border-gray-300 rounded max-h-40 overflow-y-auto">
                    @forelse ($serviceTypes as $st)
                        <label class="flex items-start gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" value="{{ $st->id }}"
                                wire:model="serviceTypeIds"
                                class="mt-0.5 rounded border-gray-300 text-blue-500 focus:ring-blue-400 shrink-0">
                            <span class="break-words">{{ $st->name }} ({{ strtoupper($st->type) }})</span>
                        </label>
                    @empty
                        <span class="text-sm text-gray-400">No service groups available.</span>
                    @endforelse
                </div>
                @error('serviceTypeIds') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="startDate" class="block mb-1 text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="startDate" wire:model="startDate"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('startDate') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="endDate" class="block mb-1 text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="endDate" wire:model="endDate"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('endDate') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="employeeFilter" class="block mb-1 text-sm font-medium text-gray-700">Service Man</label>
                <select id="employeeFilter" wire:model.live="employeeFilter"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="all">All</option>
                    <option value="individual">Individual</option>
                </select>
            </div>

            @if ($employeeFilter === 'individual')
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="selectedUserId" class="block mb-1 text-sm font-medium text-gray-700">Select User</label>
                    <select id="selectedUserId" wire:model="selectedUserId"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Select a user</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedUserId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            @endif
        </div>

        <div class="mt-4">
            <button wire:click="generateReport"
                class="w-full px-4 py-2 text-sm text-white bg-blue-500 rounded shadow hover:bg-blue-400 sm:w-auto">
                <span wire:loading.remove wire:target="generateReport">Generate Report</span>
                <span wire:loading wire:target="generateReport">Generating...</span>
            </button>
        </div>
    </div>

    {{-- Missing exchange rate errors --}}
    @if (!empty($missingExchangeRates))
        <div class="p-3 mb-4 text-red-800 bg-red-100 border border-red-300 rounded shadow sm:p-4 sm:mb-6">
            <p class="mb-2 text-sm font-semibold">Missing exchange rate for the following:</p>
            <ul class="pl-5 mb-3 text-xs list-disc sm:text-sm">
                @foreach ($missingExchangeRates as $missing)
                    <li>
                        Service Group <strong>{{ $missing['service_type'] }}</strong>
                        on <strong>{{ $missing['date'] }}</strong>
                    </li>
                @endforeach
            </ul>
            <p class="text-xs sm:text-sm">
                Please add an exchange rate covering these dates before generating this report.
            </p>
        </div>
    @endif

    {{-- Missing profit log errors --}}
    @if (!empty($missingProfitLogs))
        <div class="p-3 mb-4 text-red-800 bg-red-100 border border-red-300 rounded shadow sm:p-4 sm:mb-6">
            <p class="mb-2 text-sm font-semibold">Missing profit log setup for the following entries:</p>
            <ul class="pl-5 mb-3 text-xs list-disc sm:text-sm">
                @foreach ($missingProfitLogs as $missing)
                    <li>
                        Service Group <strong>{{ $missing['service_type'] }}</strong> &mdash;
                        Boost Type <strong>{{ $missing['boost_type'] }}</strong>
                        for user <strong>{{ $missing['user'] }}</strong>
                        on <strong>{{ $missing['date'] }}</strong>
                    </li>
                @endforeach
            </ul>
            <p class="text-xs sm:text-sm">
                Please add a profit log covering these dates for each user/service type combination above.
            </p>
        </div>
    @endif

    {{-- Report tables (one per selected Service Group) --}}
    @if ($hasGenerated)
        @if (empty($reportGroups))
            <div class="p-6 text-sm text-center text-gray-500 bg-white border border-gray-200 rounded shadow">
                No data found for the selected filters.
            </div>
        @else
            @foreach ($reportGroups as $group)
                <div class="mb-6 sm:mb-8">
                    <h2 class="flex flex-wrap items-baseline gap-x-2 mb-2 text-base font-semibold text-gray-800 sm:text-lg">
                        <span>{{ $group['service_type_name'] }}</span>
                        <span class="text-xs font-normal text-gray-400">({{ strtoupper($group['is_dollar'] ? 'dollar' : 'mmk') }})</span>
                    </h2>

                    @if (empty($group['rows']))
                        <div class="p-6 text-sm text-center text-gray-500 bg-white border border-gray-200 rounded shadow">
                            No data found for this service group.
                        </div>
                    @else
                        {{-- Desktop table --}}
                        <div class="hidden overflow-x-auto bg-white border border-gray-200 rounded shadow md:block">
                            <table class="min-w-full" style="table-layout: fixed;">
                                <colgroup>
                                    <col style="width: 6%">
                                    <col style="width: 26%">
                                    <col style="width: 16%">
                                    <col style="width: 14%">
                                    <col style="width: 14%">
                                    <col style="width: 14%">
                                    <col style="width: 10%">
                                </colgroup>
                                <thead>
                                    <tr class="w-full bg-gray-100 border-b">
                                        <th class="px-4 py-3 text-sm font-bold text-center text-gray-600">No.</th>
                                        <th class="px-4 py-3 text-sm font-bold text-center text-gray-600">Service Type</th>
                                        <th class="px-4 py-3 text-sm font-bold text-center text-gray-600">Line Total</th>
                                        <th class="px-4 py-3 text-sm font-bold text-center text-gray-600">Discount</th>
                                        <th class="px-4 py-3 text-sm font-bold text-center text-gray-600">Revenue</th>
                                        <th class="px-4 py-3 text-sm font-bold text-center text-gray-600">Employee Profit</th>
                                        <th class="px-4 py-3 text-sm font-bold text-center text-gray-600">My Profit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group['rows'] as $row)
                                        <tr class="border-b">
                                            <td class="px-4 py-3 text-sm text-center text-gray-800 truncate">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-800 truncate">{{ $row['boost_type_name'] }}</td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-800 truncate">
                                                {{ number_format($row['line_total'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-800 truncate">
                                                {{ number_format($row['discount'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-800 truncate">
                                                {{ $group['is_dollar'] ? number_format($row['revenue'], 2) : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-800 truncate">
                                                {{ number_format($row['employee_profit'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold text-center text-gray-900 truncate">
                                                {{ number_format($row['my_profit'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 border-t-2 border-gray-300">
                                        <td class="px-4 py-3 text-sm font-bold text-center text-gray-800"></td>
                                        <td class="px-4 py-3 text-sm font-bold text-center text-gray-800">Total</td>
                                        <td class="px-4 py-3 text-sm font-bold text-center text-gray-800">
                                            {{ number_format($group['totals']['line_total'], 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-bold text-center text-gray-800">
                                            {{ number_format($group['totals']['discount'], 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-bold text-center text-gray-800">
                                            {{ $group['is_dollar'] ? number_format($group['totals']['revenue'], 2) : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-bold text-center text-gray-800">
                                            {{ number_format($group['totals']['employee_profit'], 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-bold text-center text-gray-900">
                                            {{ number_format($group['totals']['my_profit'], 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Mobile cards --}}
                        <div class="space-y-3 md:hidden">
                            @foreach ($group['rows'] as $row)
                                <div class="p-3 bg-white border border-gray-200 rounded shadow">
                                    <p class="mb-2 text-sm font-semibold text-gray-800 break-words">
                                        <span class="text-gray-400">#{{ $loop->iteration }}</span>
                                        {{ $row['boost_type_name'] }}
                                    </p>
                                    <div class="grid grid-cols-2 gap-2 text-xs text-center text-gray-600">
                                        <div class="p-1.5 bg-gray-50 rounded">
                                            <span class="block text-gray-400">Line Total</span>
                                            <span class="break-words">{{ number_format($row['line_total'], 2) }}</span>
                                        </div>
                                        <div class="p-1.5 bg-gray-50 rounded">
                                            <span class="block text-gray-400">Discount</span>
                                            <span class="break-words">{{ number_format($row['discount'], 2) }}</span>
                                        </div>
                                        <div class="p-1.5 bg-gray-50 rounded">
                                            <span class="block text-gray-400">Revenue</span>
                                            <span class="break-words">{{ $group['is_dollar'] ? number_format($row['revenue'], 2) : '-' }}</span>
                                        </div>
                                        <div class="p-1.5 bg-gray-50 rounded">
                                            <span class="block text-gray-400">Employee Profit</span>
                                            <span class="break-words">{{ number_format($row['employee_profit'], 2) }}</span>
                                        </div>
                                        <div class="col-span-2 p-1.5 bg-blue-50 rounded">
                                            <span class="block text-gray-400">My Profit</span>
                                            <span class="font-semibold text-gray-900 break-words">
                                                {{ number_format($row['my_profit'], 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="p-3 bg-gray-100 border border-gray-300 rounded shadow">
                                <p class="mb-2 text-sm font-semibold text-gray-800">Total</p>
                                <div class="grid grid-cols-2 gap-2 text-xs text-center text-gray-700">
                                    <div class="p-1.5 bg-white rounded">
                                        <span class="block text-gray-400">Line Total</span>
                                        <span class="break-words">{{ number_format($group['totals']['line_total'], 2) }}</span>
                                    </div>
                                    <div class="p-1.5 bg-white rounded">
                                        <span class="block text-gray-400">Discount</span>
                                        <span class="break-words">{{ number_format($group['totals']['discount'], 2) }}</span>
                                    </div>
                                    <div class="p-1.5 bg-white rounded">
                                        <span class="block text-gray-400">Revenue</span>
                                        <span class="break-words">{{ $group['is_dollar'] ? number_format($group['totals']['revenue'], 2) : '-' }}</span>
                                    </div>
                                    <div class="p-1.5 bg-white rounded">
                                        <span class="block text-gray-400">Employee Profit</span>
                                        <span class="break-words">{{ number_format($group['totals']['employee_profit'], 2) }}</span>
                                    </div>
                                    <div class="col-span-2 p-1.5 bg-blue-100 rounded">
                                        <span class="block text-gray-400">My Profit</span>
                                        <span class="font-bold text-gray-900 break-words">
                                            {{ number_format($group['totals']['my_profit'], 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pie chart: My Profit breakdown by boost type, for this service group --}}
                        <div class="p-3 mt-4 bg-white border border-gray-200 rounded shadow sm:p-4">
                            <p class="mb-2 text-sm font-semibold text-center text-gray-700">
                                My Profit Breakdown &mdash; {{ $group['service_type_name'] }}
                            </p>
                            <div wire:ignore class="max-w-xs mx-auto">
                                <canvas id="pie-chart-{{ $group['service_type_id'] }}"></canvas>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

            {{-- Grand total across all selected service groups (only shown when more than one selected) --}}
            @if (count($reportGroups) > 1)
                <div class="p-3 mt-2 bg-blue-50 border border-blue-200 rounded shadow sm:p-4">
                    <p class="mb-3 text-sm font-bold text-blue-900">Grand Total (All Selected Service Groups)</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-center text-blue-900 sm:grid-cols-5">
                        <div class="p-2 bg-white rounded">
                            <span class="block text-blue-400">Line Total</span>
                            <span class="break-words">{{ number_format($grandTotals['line_total'], 2) }}</span>
                        </div>
                        <div class="p-2 bg-white rounded">
                            <span class="block text-blue-400">Discount</span>
                            <span class="break-words">{{ number_format($grandTotals['discount'], 2) }}</span>
                        </div>
                        <div class="p-2 bg-white rounded">
                            <span class="block text-blue-400">Revenue</span>
                            <span class="break-words">{{ number_format($grandTotals['revenue'], 2) }}</span>
                        </div>
                        <div class="p-2 bg-white rounded">
                            <span class="block text-blue-400">Employee Profit</span>
                            <span class="break-words">{{ number_format($grandTotals['employee_profit'], 2) }}</span>
                        </div>
                        <div class="col-span-2 p-2 bg-blue-100 rounded sm:col-span-1">
                            <span class="block text-blue-400">My Profit</span>
                            <span class="font-bold break-words">
                                {{ number_format($grandTotals['my_profit'], 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Pie chart: My Profit breakdown by service group --}}
                @if ($grandTotalChart)
                    <div class="p-3 mt-4 bg-white border border-gray-200 rounded shadow sm:p-4">
                        <p class="mb-2 text-sm font-semibold text-center text-gray-700">
                            My Profit Breakdown &mdash; All Service Groups
                        </p>
                        <div wire:ignore class="max-w-xs mx-auto">
                            <canvas id="pie-chart-grand-total"></canvas>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Visa Total --}}
            <div class="mt-6 overflow-hidden bg-white border border-red-200 rounded shadow">
                <div class="px-3 py-3 bg-red-50 border-b border-red-200 sm:px-4">
                    <h2 class="flex flex-wrap items-baseline gap-x-2 text-sm font-bold text-red-700 sm:text-base">
                        <span>Visa Total</span>
                        <span class="text-xs font-normal text-red-500">
                            ({{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }})
                        </span>
                    </h2>
                </div>

                @if (!empty($visaBreakdown))
                    {{-- Desktop table --}}
                    <div class="hidden overflow-x-auto md:block">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b">
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">No</th>
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">User</th>
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($visaBreakdown as $vb)
                                    <tr class="border-b">
                                        <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $vb['user_name'] }}</td>
                                        <td class="px-4 py-2 text-sm font-semibold text-center text-red-600">
                                            {{ number_format($vb['total']) }} Ks
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-red-50 border-t-2 border-red-200">
                                    <td class="px-4 py-2 text-sm font-bold text-center text-gray-800"></td>
                                    <td class="px-4 py-2 text-sm font-bold text-center text-gray-800">Total</td>
                                    <td class="px-4 py-2 text-sm font-bold text-center text-red-700">
                                        {{ number_format($visaTotal) }} Ks
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Mobile cards --}}
                    <div class="p-3 space-y-2 md:hidden">
                        @foreach ($visaBreakdown as $vb)
                            <div class="flex items-center justify-between gap-2 p-3 bg-gray-50 border border-gray-200 rounded">
                                <span class="text-sm text-gray-800 break-words">{{ $vb['user_name'] }}</span>
                                <span class="text-sm font-semibold text-red-600 whitespace-nowrap">{{ number_format($vb['total']) }} Ks</span>
                            </div>
                        @endforeach

                        <div class="flex items-center justify-between gap-2 p-3 bg-red-50 border border-red-200 rounded">
                            <span class="text-sm font-bold text-gray-800">Total</span>
                            <span class="text-sm font-bold text-red-700 whitespace-nowrap">{{ number_format($visaTotal) }} Ks</span>
                        </div>
                    </div>
                @else
                    {{-- No breakdown (individual filter mode) — just show the single total --}}
                    <div class="flex items-center justify-between gap-2 px-3 py-4 sm:px-4">
                        <span class="text-sm font-semibold text-gray-700">Total Amount</span>
                        <span class="text-base font-bold text-red-600 whitespace-nowrap sm:text-lg">{{ number_format($visaTotal) }} Ks</span>
                    </div>
                @endif
            </div>
        @endif
    @endif
</div>

@once
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
@endonce

<script>
    (function () {
        const profitReportChartInstances = {};
        const profitReportPalette = [
            '#3b82f6', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6',
            '#ec4899', '#14b8a6', '#f97316', '#6366f1', '#84cc16',
        ];

        function renderProfitReportPieChart(canvasId, labels, data) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;

            if (profitReportChartInstances[canvasId]) {
                profitReportChartInstances[canvasId].destroy();
            }

            profitReportChartInstances[canvasId] = new Chart(canvas, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: labels.map((_, i) => profitReportPalette[i % profitReportPalette.length]),
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 11 } },
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.label}: ${Number(ctx.raw).toLocaleString()}`,
                            },
                        },
                    },
                },
            });
        }

        function renderAllProfitReportCharts(pieCharts, grandTotalChart) {
            (pieCharts || []).forEach((chart) => {
                renderProfitReportPieChart(`pie-chart-${chart.id}`, chart.labels, chart.data);
            });

            if (grandTotalChart) {
                renderProfitReportPieChart('pie-chart-grand-total', grandTotalChart.labels, grandTotalChart.data);
            }
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('report-charts-updated', (event) => {
                // Livewire 3 passes dispatched params as a single object.
                const pieCharts = event.pieCharts ?? event[0]?.pieCharts;
                const grandTotalChart = event.grandTotalChart ?? event[0]?.grandTotalChart;

                // Give the DOM a tick to settle after Livewire's morph before drawing.
                setTimeout(() => renderAllProfitReportCharts(pieCharts, grandTotalChart), 50);
            });
        });
    })();
</script>
