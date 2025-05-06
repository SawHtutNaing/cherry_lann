<div class="container pt-5 mx-auto mt-8">
    @if (!$isExport)

        <h1 class="mb-6 text-2xl font-semibold">Data Inputs</h1>

        <div class="p-6 mx-auto bg-white rounded-lg shadow-lg ">

            {{-- <a href="{{ route('data-inputs.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 text-white bg-blue-700 rounded shadow hover:bg-blue-800">
                Create New Data Input
            </a> --}}

            <button wire:click='reprotExcel'
                class="inline-flex items-center justify-center px-4 py-2 text-white bg-green-700 rounded shadow hover:bg-green-800">
                Excel
            </button>

            {{-- <div class="flex flex-col justify-end mt-3 space-y-4 md:flex-row md:space-y-0 md:space-x-4">


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
                <div class="w-full">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Service By </label>
                    <select wire:model="service_by"
                        class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">

                        <option value="">All</option>
                        @foreach ($servicesBys as $servicesBy)
                            <option value="{{ $servicesBy->id }}">{{ $servicesBy->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="w-full">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">BoostType </label>
                    <select wire:model="boosttype"
                        class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        <option value="">All</option>

                        @foreach ($boostTypes as $boostType)
                            <option value="{{ $boostType->id }}">{{ $boostType->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="w-full">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Status </label>
                    <select wire:model="status_at"
                        class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        <option value="">All</option>


                        <option value="1">
                            Charge
                        </option>
                        <option value="2">
                            Refund
                        </option>

                    </select>

                </div>






                <div class="flex items-end mt-4">


                    <button id="filterBtn" wire:click='filterData()'
                        class="w-full px-4 py-2 font-semibold text-white transition duration-200 bg-blue-600 rounded-md shadow hover:bg-blue-700">
                        Filter
                    </button>
                </div>
            </div> --}}
            <div class="flex flex-col justify-start mt-6 space-y-6 md:flex-row md:space-y-0 md:space-x-6">

                <div class="w-full md:w-1/4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" id="start_date" wire:model='startDate'
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="w-full md:w-1/4">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" id="end_date" wire:model='endDate'
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="w-full md:w-1/4">
                    <label for="service_by" class="block text-sm font-medium text-gray-700">Service By</label>
                    <select
                    @disabled(auth()->user()->role != 'admin')

                    wire:model="service_by"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        @foreach ($servicesBys as $servicesBy)
                            <option value="{{ $servicesBy->id }}">{{ $servicesBy->name }}</option>
                        @endforeach
                    </select>
                </div>

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

                <div class="flex items-end mt-6 md:w-1/4">
                    <button id="filterBtn" wire:click='filterData()'
                        class="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                        Filter
                    </button>
                </div>

            </div>



            <!-- Filter Button -->


        </div>
    @endif
    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-100 border-b">
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">No</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Page Name</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Serviced By</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Service Type</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Start Date</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Quantity</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Total Amount</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Status</th>
                    {{-- @if (!$isExport)
                        <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Actions</th>
                    @endif --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($dataInputs as $dataInput)
                    <tr class="border-b">

                        <td class="px-6 py-4 text-sm text-gray-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->page_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->boostType->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($dataInput->start_date)->format('d/m/y') }}</td>

                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->amount }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->mm_kyat }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->total_amount }}</td>

                        <td
                            class="px-6 py-4 text-sm {{ $dataInput->status->name == 'Charge' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $dataInput->status->label() }}
                        </td>
                        @if (!$isExport)
                            {{-- <td class="px-6 py-4">
                                <a href="{{ route('data-inputs.edit', $dataInput->id) }}"
                                    class="px-4 py-2 text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">Edit</a>
                                <button wire:click="delete({{ $dataInput->id }})"
                                    class="px-4 py-2 text-white bg-red-500 rounded shadow hover:bg-red-400">Delete</button>
                            </td> --}}
                        @endif
                    </tr>
                @endforeach

            </tbody>
        </table>



        <table class="min-w-full bg-white border border-gray-200">

            <thead>
                <tr class="w-full bg-gray-100 border-b">
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Campaing</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Charge</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Refund</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Total</th>

                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="px-6 py-4 text-sm text-gray-800">
                        {{ $dataInputs->count() }}

                    </td>

                    <td class="px-6 py-4 text-sm text-gray-800">
                        {{ $charges }}

                    </td>

                    <td class="px-6 py-4 text-sm text-gray-800">
                        {{ $refund }}

                    </td>


                    <td class="px-6 py-4 text-sm text-gray-800">
                        {{ $charges - $refund }}

                    </td>





                </tr>
            </tbody>
        </table>
    </div>
</div>
