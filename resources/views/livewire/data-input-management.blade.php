<div class="container mx-auto mt-8">
    <h1 class="mb-6 text-2xl font-semibold">Data Inputs</h1>

    <div class="p-6 mx-auto bg-white rounded-lg shadow-lg ">

        <a href="{{ route('data-inputs.create') }}"
            class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">Create New Data
            Input</a>
        <div class="flex flex-col justify-end mt-3 space-y-4 md:flex-row md:space-y-0 md:space-x-4">


            <div class="w-full">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="start_date" wire:model='startDate'
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- End Date -->
            <div class="w-full">
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="end_date" wire:model='endDate'
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end mt-4">


                <button id="filterBtn" wire:click='filterData()'
                    class="w-full px-4 py-2 font-semibold text-white transition duration-200 bg-blue-600 rounded-md shadow hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </div>

        <!-- Filter Button -->


    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-100 border-b">
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">No</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Page Name</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Boost Type</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Start Date</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Status</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataInputs as $dataInput)
                    <tr class="border-b" wire:key='{{ $dataInput->id }}'>

                        <td class="px-6 py-4 text-sm text-gray-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->page_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->boostType->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($dataInput->start_date)->format('d/m/y') }}</td>

                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->amount }}</td>

                        <td
                            class="px-6 py-4 text-sm {{ $dataInput->status->name == 'Charge' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $dataInput->status->label() }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('data-inputs.edit', $dataInput->id) }}"
                                class="px-4 py-2 text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">Edit</a>
                            <button wire:confirm='Are You Sure Want To Delete ?'
                                wire:click="delete({{ $dataInput->id }})"
                                class="px-4 py-2 text-white bg-red-500 rounded shadow hover:bg-red-400">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
