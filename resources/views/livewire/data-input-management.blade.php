<div class="container mx-auto mt-8 px-4">
    <h1 class="mb-6 text-2xl font-semibold">Data Inputs</h1>

    <div class="p-6 bg-white rounded-lg shadow-lg">
        <a href="{{ route('data-inputs.create') }}"
            class="inline-block mb-4 px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">Create New Data Input</a>

        <div class="flex flex-col gap-4 md:flex-row md:flex-wrap">
            <div class="w-full md:w-1/4">
                <label for="boosttype" class="block text-sm font-medium text-gray-700">Service Type</label>
                <select wire:model="boosttype"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All</option>
                    @foreach ($boostTypes as $boostType)
                        <option value="{{ $boostType->id }}">{{ $boostType->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-1/4">
                <label for="status_at" class="block text-sm font-medium text-gray-700">Status</label>
                <select wire:model="status_at"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All</option>
                    <option value="1">Charge</option>
                    <option value="2">Refund</option>
                    <option value="3">Pending</option>
                </select>
            </div>

            <div class="w-full md:w-1/4">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="start_date" wire:model='startDate'
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="w-full md:w-1/4">
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="end_date" wire:model='endDate'
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-full md:w-1/4">
                <label for="cus_name_search" class="block text-sm font-medium text-gray-700">Cus Name Search</label>
                <input type="text" id="cus_name_search" wire:model='cus_name_search'
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>


            <div class="w-full md:w-1/4">
                <label for="check_remark" class="block text-sm font-medium text-gray-700">Only Remark</label>
                <input type="checkbox" id="check_remark" wire:model='check_remark'
                    class=" border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    >
            </div>



            <div class="w-full md:w-auto flex items-end">
                <button id="filterBtn" wire:click='filterData()'
                    class="w-full md:w-auto px-4 py-2 font-semibold text-white bg-blue-600 rounded-md shadow hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </div>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-max w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">No</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Action</th>

                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Customer Name</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Page Name</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Phone</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Service Type</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Start Date</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Quantity</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Discount</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Total Amount</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Status</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Remark</th>

                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Created At</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Updated At</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Export</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($dataInputs as $dataInput)
                    <tr class="border-b" wire:key='{{ $dataInput->id }}'>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 flex flex-col gap-y-2">
                            <a href="{{ route('data-inputs.edit', $dataInput->id) }}"
                                class="px-4 py-2 text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">Edit</a>

                                 <button wire:confirm='Are You Sure Want To Copy ?'
                                wire:click="copy({{ $dataInput->id }})"
                                class="px-4 py-2 text-white bg-green-500 rounded shadow hover:bg-red-400">Copy</button>


                            <button wire:confirm='Are You Sure Want To Delete ?'
                                wire:click="delete({{ $dataInput->id }})"
                                class="px-4 py-2 text-white bg-red-500 rounded shadow hover:bg-red-400">Delete</button>
                        </td>






                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->customer_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->page_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->phone ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->boostType->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $dataInput->start_date ? \Carbon\Carbon::parse($dataInput->start_date)->format('d/m/y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->amount ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->mm_kyat }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->discount }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->total_amount }}</td>
                        <td class="px-6 py-4 text-sm @if($dataInput->status->name == 'Charge') text-green-600 @elseif($dataInput->status->name == 'Refund') text-red-600 @else text-yellow-600 @endif">
                            {{ $dataInput->status->label() }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->is_remark == 1  ? '✅ ' : '' }}</td>

                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($dataInput->created_at)->format('d/m/y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($dataInput->updated_at)->format('d/m/y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <button class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400"
                                wire:click='export({{ $dataInput->id }})'>
                                Export
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
