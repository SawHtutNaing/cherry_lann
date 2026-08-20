<div class="container mx-auto mt-8 px-4">
    <h1 class="mb-6 text-xl font-semibold sm:text-2xl">Site Profit Report</h1>

    {{-- Filter form --}}
    <div class="p-4 mb-6 bg-white border border-gray-200 rounded shadow">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label for="startDate" class="block mb-1 text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="startDate" wire:model="startDate"
                    class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('startDate') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="endDate" class="block mb-1 text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="endDate" wire:model="endDate"
                    class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('endDate') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-end">
                <button wire:click="generateReport"
                    class="w-full px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400 sm:w-auto">
                    <span wire:loading.remove wire:target="generateReport">Generate Report</span>
                    <span wire:loading wire:target="generateReport">Generating...</span>
                </button>
            </div>
            @if ($hasGenerated && !empty($serviceTypeGroups))
                <div class="flex items-end">
                    <button wire:click="exportExcel"
                        class="w-full px-4 py-2 text-white bg-emerald-600 rounded shadow hover:bg-emerald-500 sm:w-auto">
                        <span wire:loading.remove wire:target="exportExcel">Export Excel</span>
                        <span wire:loading wire:target="exportExcel">Exporting...</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Missing exchange rate errors --}}
    @if (!empty($missingExchangeRates))
        <div class="p-4 mb-6 text-red-800 bg-red-100 border border-red-300 rounded shadow">
            <p class="mb-2 font-semibold">Missing exchange rate for the following:</p>
            <ul class="pl-5 mb-3 text-sm list-disc">
                @foreach ($missingExchangeRates as $missing)
                    <li>
                        Service type <strong>{{ $missing['service_type'] }}</strong>
                        on <strong>{{ $missing['date'] }}</strong>
                    </li>
                @endforeach
            </ul>
            <p class="text-sm">Please add exchange rates covering these dates before generating this report.</p>
        </div>
    @endif

    {{-- Missing profit log errors --}}
    @if (!empty($missingProfitLogs))
        <div class="p-4 mb-6 text-red-800 bg-red-100 border border-red-300 rounded shadow">
            <p class="mb-2 font-semibold">Missing profit log setup for the following entries:</p>
            <ul class="pl-5 mb-3 text-sm list-disc">
                @foreach ($missingProfitLogs as $missing)
                    <li>
                        Boost type <strong>{{ $missing['boost_type'] }}</strong>
                        for user <strong>{{ $missing['user'] }}</strong>
                        on <strong>{{ $missing['date'] }}</strong>
                    </li>
                @endforeach
            </ul>
            <p class="text-sm">Please add a profit log covering these dates for each user/boost type combination above.</p>
        </div>
    @endif

    {{-- Report --}}
    @if ($hasGenerated)
        @if (empty($serviceTypeGroups))
            <div class="p-6 text-sm text-center text-gray-500 bg-white border border-gray-200 rounded shadow">
                No service types with boost types found.
            </div>
        @else
            @foreach ($serviceTypeGroups as $group)
                @php $isDollar = $group['type'] === 'dollar'; @endphp
                <div class="mb-6 overflow-hidden bg-white border border-gray-200 rounded shadow" x-data="{ open: false }">
                    <div class="flex items-center justify-between px-4 py-3 bg-gray-100 border-b">
                        <h2 class="text-sm font-semibold text-gray-800 sm:text-base">
                            {{ $group['service_type_name'] }}
                            <span class="ml-2 text-xs font-normal text-gray-500 uppercase">({{ $group['type'] }})</span>
                        </h2>
                        <button @click="open = !open"
                            class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded hover:bg-blue-200">
                            <span x-show="!open">Show Details</span>
                            <span x-show="open" style="display:none">Hide Details</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead x-show="open" style="display:none">
                                <tr class="bg-gray-50 border-b">
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">No</th>
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">Boost Type</th>
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">Line Total</th>
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">Discount</th>
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">Discount (MMK)</th>
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">Revenue</th>
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">E_Profit</th>
                                    <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">M_Profit</th>
                                </tr>
                            </thead>
                            <tbody x-show="open" style="display:none">
                                @foreach ($group['rows'] as $row)
                                    <tr class="border-b">
                                        <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $row['boost_type_name'] }}</td>
                                        <td class="px-4 py-2 text-sm text-center text-gray-800">
                                            {{ number_format($row['line_total'], 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-center text-gray-800">
                                            {{ $isDollar ? number_format($row['discount'], 2) : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-center text-gray-800">
                                            {{ $isDollar ? number_format($row['discount_mmk'], 2) : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-center text-gray-800">
                                            {{ $isDollar ? number_format($row['revenue'], 2) : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-center text-gray-800">
                                            {{ number_format($row['employee_profit'], 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-sm font-medium text-center text-gray-900">
                                            {{ number_format($row['my_profit'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 border-t-2 border-gray-300">
                                    <td class="px-4 py-2 text-sm font-bold text-center text-gray-800">Subtotal</td>
                                    <td class="px-4 py-2 text-sm font-bold text-center text-gray-800"></td>
                                    <td class="px-4 py-2 text-sm font-bold text-center text-gray-800">
                                        {{ number_format($group['subtotals']['line_total'], 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-sm font-bold text-center text-gray-800">
                                        {{ $isDollar ? number_format($group['subtotals']['discount'], 2) : '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm font-bold text-center text-gray-800">
                                        {{ $isDollar ? number_format($group['subtotals']['discount_mmk'], 2) : '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm font-bold text-center text-gray-800">
                                        {{ $isDollar ? number_format($group['subtotals']['revenue'], 2) : '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm font-bold text-center text-gray-800">
                                        {{ number_format($group['subtotals']['employee_profit'], 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-sm font-bold text-center text-gray-900">
                                        {{ number_format($group['subtotals']['my_profit'], 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endforeach

            {{-- Combined profit before expenses --}}
            <div class="flex items-center justify-between p-4 mb-6 text-white bg-green-600 rounded shadow">
                <span class="text-sm font-semibold sm:text-base">Before Expense</span>
                <span class="text-sm font-bold sm:text-base">
                    {{ number_format($grandProfitTotal, 2) }}
                </span>
            </div>

            {{-- Expense breakdown --}}
            <div class="mb-6 overflow-hidden bg-white border border-gray-200 rounded shadow">
                <div class="px-4 py-3 bg-gray-100 border-b">
                    <h2 class="text-sm font-semibold text-gray-800 sm:text-base">Expenses</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">No</th>
                                <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">Category</th>
                                <th class="px-4 py-2 text-sm font-bold text-center text-gray-600">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expenseRows as $expense)
                                <tr class="border-b">
                                    <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $expense['category'] }}</td>
                                    <td class="px-4 py-2 text-sm text-center text-gray-800">
                                        {{ number_format($expense['total'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm text-center text-gray-500">
                                        No expenses recorded in this period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Total expense summary --}}
            <div class="flex items-center justify-between p-4 mb-6 text-white bg-orange-500 rounded shadow">
                <span class="text-sm font-semibold sm:text-base">Total Expense</span>
                <span class="text-sm font-bold sm:text-base">
                    {{ number_format($expenseGrandTotal, 2) }}
                </span>
            </div>

            {{-- Final net profit --}}
            <div class="flex items-center justify-between p-4 text-white bg-blue-600 rounded shadow">
                <span class="text-sm font-semibold sm:text-base">Summary Net Profit</span>
                <span class="text-lg font-bold sm:text-xl">{{ number_format($netProfit, 2) }}</span>
            </div>
        @endif
    @endif
</div>
