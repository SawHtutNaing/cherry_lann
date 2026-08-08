<table class="min-w-full bg-white border border-gray-200 mt-4">
    <thead>
        <tr class="w-full bg-gray-100 border-b">
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Campaing</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Charge</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Refund</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Total</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Pending</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Overall Total</th>
        </tr>
    </thead>
    <tbody>
        <tr class="border-b">
            <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInputs->count() }}</td>
            <td class="px-6 py-4 text-sm text-gray-800">{{ $charges }}</td>
            <td class="px-6 py-4 text-sm text-gray-800">{{ $refund }}</td>
            <td class="px-6 py-4 text-sm text-gray-800">{{ $charges - $refund }}</td>
            <td class="px-6 py-4 text-sm text-gray-800">{{ $pending_total }}</td>
            <td class="px-6 py-4 text-sm text-gray-800">{{ $overall_total }}</td>
        </tr>
    </tbody>
</table>

<table class="min-w-full bg-white border border-gray-200 mt-6">
    <thead>
        <tr class="w-full bg-gray-100 border-b">
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">No</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Page Name</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Cus Name</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Serviced By</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Service Type</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Start Date</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Quantity</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Amount</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Discount</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Total Amount</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Status</th>
            <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Remark</th>
        </tr>
    </thead>
    <tbody>
        {{-- $dataInputs is a flat (non-paginated) collection for export, so
             numbering just starts from 0. Each DataInput can have several
             items (service types) — one row per item. If a DataInput has no
             items yet, still emit a single fallback row so it isn't dropped. --}}
        @php $rowNum = 0; @endphp
        @foreach ($dataInputs as $dataInput)
            @php
                $rowItems = $dataInput->items->isEmpty() ? collect([null]) : $dataInput->items;
            @endphp
            @foreach ($rowItems as $item)
                @php $rowNum++; @endphp
                <tr class="border-b">
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $rowNum }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->page_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->customer_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $item?->boostType->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">
                        {{ $item && $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d/m/y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $item->amount ?? 0 }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $item->mm_kyat ?? 0 }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $item->discount ?? 0 }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $item->line_total ?? $dataInput->total_amount }}</td>
                    <td class="px-6 py-4 text-sm {{ $dataInput->status->name == 'Charge' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $dataInput->status->label() }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->remark }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
