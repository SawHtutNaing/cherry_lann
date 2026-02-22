<div class="container pt-5 mx-auto mt-8">
    @if (!$isExport)

        <h1 class="mb-6 text-2xl font-semibold">Data Inputs</h1>

        <div class="p-6 mx-auto bg-white rounded-lg shadow-lg ">

            <button wire:click='reprotExcel'
                class="inline-flex items-center justify-center px-4 py-2 text-white bg-green-700 rounded shadow hover:bg-green-800">
                Excel
            </button>

            <div class="flex flex-col justify-start mt-6 space-y-6 md:flex-row md:space-y-0 md:space-x-6">

                <div class="w-full md:w-1/4">
                    <label for="cus_name_search" class="block text-sm font-medium text-gray-700">Cus Name Search</label>
                    <input type="text" id="cus_name_search" wire:model='cus_name_search'
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

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
                    <select multiple wire:model="boosttype"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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

        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-200 mt-4">
        <thead>
            <tr class="w-full bg-gray-100 border-b">
                <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Campaing</th>
                <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Charge</th>
                <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Refund</th>
                <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Total</th>
                <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Pending</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b">
                <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInputs->count() }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $charges }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $refund }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $charges - $refund }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $pending_total }}</td>
            </tr>
        </tbody>
    </table>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
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
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Client Image</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Cherry Lann Image</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataInputs as $dataInput)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->page_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->customer_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->boostType->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($dataInput->start_date)->format('d/m/y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->amount }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->mm_kyat }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->discount }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $dataInput->total_amount }}</td>
                        <td class="px-6 py-4 text-sm {{ $dataInput->status->name == 'Charge' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $dataInput->status->label() }}
                        </td>

                        {{-- Client Image (preview only) --}}
                        <td class="px-6 py-4 text-sm text-gray-800">
                            @if($dataInput->client_side_image)
                                <img src="{{ Storage::disk('public')->url($dataInput->client_side_image) }}"
                                     class="w-16 h-16 object-cover rounded cursor-pointer border border-gray-200 hover:opacity-80 transition"
                                     onclick="openReportImageModal(this.src)"
                                     title="Click to preview">
                            @else
                                <span class="text-xs text-gray-400 italic">No image</span>
                            @endif
                        </td>

                        {{-- Cherry Lann / Service Image (preview only) --}}
                        <td class="px-6 py-4 text-sm text-gray-800">
                            @if($dataInput->service_side_image)
                                <img src="{{ Storage::disk('public')->url($dataInput->service_side_image) }}"
                                     class="w-16 h-16 object-cover rounded cursor-pointer border border-gray-200 hover:opacity-80 transition"
                                     onclick="openReportImageModal(this.src)"
                                     title="Click to preview">
                            @else
                                <span class="text-xs text-gray-400 italic">No image</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Image Preview Modal --}}
    <div id="reportImageModal"
         class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex flex-col items-center justify-center px-4"
         onclick="closeReportImageModal()">
        <button onclick="closeReportImageModal(); event.stopPropagation();"
                class="absolute top-4 right-4 z-10 flex items-center gap-1 px-4 py-2 bg-white text-gray-800 text-sm font-medium rounded-full shadow-lg hover:bg-gray-100 transition">
            ✕ Close
        </button>
        <img id="reportImageModalImg" src="" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl object-contain">
        <p class="mt-3 text-white text-xs opacity-60">Tap anywhere or press Close to dismiss</p>
    </div>

    <script>
        function openReportImageModal(src) {
            document.getElementById('reportImageModalImg').src = src;
            document.getElementById('reportImageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeReportImageModal() {
            document.getElementById('reportImageModal').classList.add('hidden');
            document.getElementById('reportImageModalImg').src = '';
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeReportImageModal();
        });
    </script>
</div>
