<div class="container mx-auto mt-8 px-4">
    <h1 class="mb-6 text-xl font-semibold sm:text-2xl">Profit Report</h1>

    {{-- Filter form --}}
    <div class="p-4 mb-6 bg-white border border-gray-200 rounded shadow">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label for="serviceTypeId" class="block mb-1 text-sm font-medium text-gray-700">Service Type</label>
                <select id="serviceTypeId" wire:model="serviceTypeId"
                    class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Select a service type</option>
                    @foreach ($serviceTypes as $st)
                        <option value="{{ $st->id }}">{{ $st->name }} ({{ strtoupper($st->type) }})</option>
                    @endforeach
                </select>
                @error('serviceTypeId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

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

            <div>
                <label for="employeeFilter" class="block mb-1 text-sm font-medium text-gray-700">Service Man</label>
                <select id="employeeFilter" wire:model.live="employeeFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="all">All</option>
                    <option value="individual">Individual</option>
                </select>
            </div>

            @if ($employeeFilter === 'individual')
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="selectedUserId" class="block mb-1 text-sm font-medium text-gray-700">Select User</label>
                    <select id="selectedUserId" wire:model="selectedUserId"
                        class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
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
                class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                <span wire:loading.remove wire:target="generateReport">Generate Report</span>
                <span wire:loading wire:target="generateReport">Generating...</span>
            </button>
        </div>
    </div>

    {{-- Missing exchange rate errors --}}
    @if (!empty($missingExchangeRates))
        <div class="p-4 mb-6 text-red-800 bg-red-100 border border-red-300 rounded shadow">
            <p class="mb-2 font-semibold">Missing exchange rate for the following date(s):</p>
            <ul class="pl-5 mb-3 text-sm list-disc">
                @foreach ($missingExchangeRates as $date)
                    <li>{{ $date }}</li>
                @endforeach
            </ul>
            <p class="text-sm">
                Please add an exchange rate covering these dates before generating this report.
            </p>
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
            <p class="text-sm">
                Please add a profit log covering these dates for each user/boost type combination above.
            </p>
        </div>
    @endif

    {{-- Report table --}}
    @if ($hasGenerated)
        @if (empty($reportRows))
            <div class="p-6 text-sm text-center text-gray-500 bg-white border border-gray-200 rounded shadow">
                No data found for the selected filters.
            </div>
        @else
            @php $isDollar = $serviceType->type === 'dollar'; @endphp

            {{-- Desktop table --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="w-full bg-gray-100 border-b">
                            <th class="px-4 py-3 text-sm font-medium text-left text-gray-600">Boost Type</th>
                            <th class="px-4 py-3 text-sm font-medium text-right text-gray-600">Line Total</th>
                            @if ($isDollar)
                                <th class="px-4 py-3 text-sm font-medium text-right text-gray-600">Discount</th>
                                <th class="px-4 py-3 text-sm font-medium text-right text-gray-600">Revenue</th>
                            @endif
                            <th class="px-4 py-3 text-sm font-medium text-right text-gray-600">Employee Profit</th>
                            <th class="px-4 py-3 text-sm font-medium text-right text-gray-600">My Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportRows as $row)
                            <tr class="border-b">
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $row['boost_type_name'] }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-800">
                                    {{ number_format($row['line_total'], 2) }}
                                </td>
                                @if ($isDollar)
                                    <td class="px-4 py-3 text-sm text-right text-gray-800">
                                        {{ number_format($row['discount'], 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-800">
                                        {{ number_format($row['revenue'], 2) }}
                                    </td>
                                @endif
                                <td class="px-4 py-3 text-sm text-right text-gray-800">
                                    {{ number_format($row['employee_profit'], 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-right text-gray-900">
                                    {{ number_format($row['my_profit'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 border-t-2 border-gray-300">
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800">Total</td>
                            <td class="px-4 py-3 text-sm font-semibold text-right text-gray-800">
                                {{ number_format($reportTotals['line_total'], 2) }}
                            </td>
                            @if ($isDollar)
                                <td class="px-4 py-3 text-sm font-semibold text-right text-gray-800">
                                    {{ number_format($reportTotals['discount'], 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-right text-gray-800">
                                    {{ number_format($reportTotals['revenue'], 2) }}
                                </td>
                            @endif
                            <td class="px-4 py-3 text-sm font-semibold text-right text-gray-800">
                                {{ number_format($reportTotals['employee_profit'], 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-right text-gray-900">
                                {{ number_format($reportTotals['my_profit'], 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="space-y-4 md:hidden">
                @foreach ($reportRows as $row)
                    <div class="p-4 bg-white border border-gray-200 rounded shadow">
                        <p class="mb-2 text-sm font-semibold text-gray-800">{{ $row['boost_type_name'] }}</p>
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <div>
                                <span class="block text-gray-400">Line Total</span>
                                {{ number_format($row['line_total'], 2) }}
                            </div>
                            @if ($isDollar)
                                <div>
                                    <span class="block text-gray-400">Discount</span>
                                    {{ number_format($row['discount'], 2) }}
                                </div>
                                <div>
                                    <span class="block text-gray-400">Revenue</span>
                                    {{ number_format($row['revenue'], 2) }}
                                </div>
                            @endif
                            <div>
                                <span class="block text-gray-400">Employee Profit</span>
                                {{ number_format($row['employee_profit'], 2) }}
                            </div>
                            <div>
                                <span class="block text-gray-400">My Profit</span>
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($row['my_profit'], 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="p-4 bg-gray-100 border border-gray-300 rounded shadow">
                    <p class="mb-2 text-sm font-semibold text-gray-800">Total</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-700">
                        <div>
                            <span class="block text-gray-400">Line Total</span>
                            {{ number_format($reportTotals['line_total'], 2) }}
                        </div>
                        @if ($isDollar)
                            <div>
                                <span class="block text-gray-400">Discount</span>
                                {{ number_format($reportTotals['discount'], 2) }}
                            </div>
                            <div>
                                <span class="block text-gray-400">Revenue</span>
                                {{ number_format($reportTotals['revenue'], 2) }}
                            </div>
                        @endif
                        <div>
                            <span class="block text-gray-400">Employee Profit</span>
                            {{ number_format($reportTotals['employee_profit'], 2) }}
                        </div>
                        <div>
                            <span class="block text-gray-400">My Profit</span>
                            <span class="font-bold text-gray-900">
                                {{ number_format($reportTotals['my_profit'], 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
