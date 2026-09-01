<div class="container pt-5 mx-auto mt-8">
    @if (!$isExport)

        <h1 class="mb-6 text-2xl font-semibold">Data Inputs</h1>

        <div class="p-6 mx-auto bg-white rounded-lg shadow-lg ">

            <button wire:click='reprotExcel'
                wire:loading.attr="disabled"
                wire:target="reprotExcel"
                class="inline-flex items-center justify-center px-4 py-2 text-white bg-green-700 rounded shadow hover:bg-green-800 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="reprotExcel">Excel</span>
                <span wire:loading wire:target="reprotExcel">Exporting… please wait</span>
            </button>

            <div class="flex flex-col justify-start mt-6 space-y-6 md:flex-row md:flex-wrap md:space-y-0 md:space-x-6 md:gap-y-6">

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

                {{-- NEW — Days ≥ filter, mirrors the dashboard's days_count filter --}}
                <div class="w-full md:w-1/4">
                    <label for="days_count" class="block text-sm font-medium text-gray-700">Days ≥</label>
                    <input type="number" id="days_count" min="0" wire:model="days_count" placeholder="e.g. 7"
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

                <div class="w-full md:w-1/4" x-data="{ open: false }">
    <style>[x-cloak] { display: none !important; }</style>

    <label class="block text-sm font-medium text-gray-700">Service Type</label>

    <div class="relative">
        <button type="button" @click="open = !open"
            class="flex items-center justify-between w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <span class="text-gray-700 truncate">
                @if (count($boosttype) === 0)
                    All
                @elseif (count($boosttype) === 1)
                    {{ $boostTypes->firstWhere('id', $boosttype[0])->name ?? '1 selected' }}
                @else
                    {{ count($boosttype) }} selected
                @endif
            </span>
            <svg class="w-4 h-4 ml-2 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" @click.outside="open = false" x-cloak
            class="absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg">

            <div class="flex justify-between px-3 py-2 text-xs border-b border-gray-100">
                <button type="button"
                    wire:click="$set('boosttype', [{{ $boostTypes->pluck('id')->implode(',') }}])"
                    class="text-blue-600 hover:underline">Select All</button>
                <button type="button" wire:click="$set('boosttype', [])"
                    class="text-gray-500 hover:underline">Clear</button>
            </div>

            <div class="p-2 space-y-1 overflow-y-auto max-h-48">
                @forelse ($boostTypes as $boostType)
    <label wire:key="boosttype-{{ $boostType->id }}" class="flex items-center gap-2 px-2 py-1 text-sm text-gray-700 rounded cursor-pointer hover:bg-gray-50">
        <input type="checkbox" value="{{ $boostType->id }}" wire:model.live="boosttype"
            class="rounded border-gray-300 text-blue-500 focus:ring-blue-400">
        {{ $boostType->name }}
    </label>
@empty
    <span class="block px-2 py-1 text-sm text-gray-400">No service types available.</span>
@endforelse
            </div>
        </div>
    </div>
</div>

                <div class="w-full md:w-1/4">
                    <label for="status_at" class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model="status_at"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        <option value="1">Charge</option>
                        <option value="2">Refund</option>
                        <option value="3">Pending</option>
                        <option value="4">Ongoing</option>
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
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Campaing</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Charge</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Refund</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Total</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Pending</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b">
                <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $isExport ? $dataInputs->count() : $totalCount }}</td>
                <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $charges }}</td>
                <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $refund }}</td>
                <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $charges - $refund }}</td>
                <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $pending_total }}</td>
            </tr>
        </tbody>
    </table>

<div class="mt-6 overflow-x-auto" wire:loading.class="opacity-50" wire:target="filterData,reprotExcel,previousPage,nextPage,gotoPage">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="w-full bg-gray-100 border-b">
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">No</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Page Name</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Cus Name</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Serviced By</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600 min-w-[280px]">Items</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Total Amount</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Days</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Status</th>
@if (!$isExport)
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600 w-[150px]">Client Image</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600 w-[150px]">Cherry Lann Image</th>
@endif
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataInputs as $dataInput)
                <tr class="border-b align-top">
                    <td class="px-6 py-4 text-sm text-center text-gray-800">
                        {{ $isExport ? $loop->iteration : (($dataInputs->currentPage() - 1) * $dataInputs->perPage() + $loop->iteration) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $dataInput->page_name }}</td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $dataInput->customer_name }}</td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $dataInput->user->name ?? 'N/A' }}</td>

                    <td class="px-6 py-4">
                        <div class="rounded-md border border-gray-200 divide-y divide-gray-100 overflow-hidden">
                            @forelse ($dataInput->items as $item)
                                <div class="px-3 py-2 bg-white text-xs leading-tight">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-semibold text-gray-800">{{ $item->boostType->name ?? 'N/A' }}</span>
                                        <span class="text-gray-400 tabular-nums">
                                            {{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d/m/y') : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-gray-500 mt-0.5">
                                        <span>Qty {{ $item->amount }}</span>
                                        <span>Price {{ number_format($item->mm_kyat) }}</span>
                                        <span>Disc {{ number_format($item->discount) }}</span>
                                        <span class="ml-auto font-semibold text-gray-800">{{ number_format($item->line_total) }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="px-3 py-2 text-xs text-gray-400 text-center">No items</div>
                            @endforelse
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm font-semibold text-center text-gray-800">{{ number_format($dataInput->items->sum('line_total')) }}</td>

                    {{-- NEW — Days column, same color-coded badge as the dashboard --}}
                    <td class="px-6 py-4 text-sm text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                            @if($dataInput->status->name == 'Charge') bg-green-100 text-green-700
                            @elseif($dataInput->status->name == 'Refund') bg-red-100 text-red-700
                            @else bg-amber-100 text-amber-700 @endif">
                            {{ (int) floor(\Carbon\Carbon::parse($dataInput->created_at)->diffInHours(now()) / 24) }}d
                        </span>
                    </td>

                    <td class="px-6 py-4 text-sm text-center {{ $dataInput->status->name == 'Charge' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $dataInput->status->label() }}
                    </td>

@if (!$isExport)
                    {{-- Client Images — dashboard-style gallery (multi-image, add/delete/preview) --}}
                    <td class="px-6 py-4">
                        <div id="report-gallery-client-{{ $dataInput->id }}">
                            <div class="flex flex-wrap gap-1 justify-center mb-1.5" id="report-images-client-{{ $dataInput->id }}">
                                @forelse ($dataInput->clientImages as $image)
                                    <div class="relative" id="report-image-{{ $image->id }}">
                                        <img src="{{ $image->url }}"
                                             class="w-12 h-12 object-cover rounded-lg cursor-pointer border border-gray-200 hover:opacity-80 transition-opacity"
                                             onclick="openReportImageModal('{{ $image->url }}', 'report-images-client-{{ $dataInput->id }}')">
                                        <button type="button" onclick="confirmDeleteReportImage({{ $dataInput->id }}, {{ $image->id }})"
                                            class="absolute -top-1.5 -right-1.5 w-4 h-4 flex items-center justify-center text-[10px] text-white bg-red-500 rounded-full shadow hover:bg-red-600">
                                            ✕
                                        </button>
                                    </div>
                                @empty
                                    <span class="text-xs text-gray-400 italic">No images</span>
                                @endforelse
                            </div>
                            <button type="button" onclick="triggerReportUpload({{ $dataInput->id }}, 'client')"
                                class="w-full inline-flex items-center justify-center gap-1 px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 transition-colors whitespace-nowrap">
                                + Add
                            </button>
                        </div>
                    </td>

                    {{-- Cherry Lann / Service Images --}}
                    <td class="px-6 py-4">
                        <div id="report-gallery-service-{{ $dataInput->id }}">
                            <div class="flex flex-wrap gap-1 justify-center mb-1.5" id="report-images-service-{{ $dataInput->id }}">
                                @forelse ($dataInput->serviceImages as $image)
                                    <div class="relative" id="report-image-{{ $image->id }}">
                                        <img src="{{ $image->url }}"
                                             class="w-12 h-12 object-cover rounded-lg cursor-pointer border border-gray-200 hover:opacity-80 transition-opacity"
                                             onclick="openReportImageModal('{{ $image->url }}', 'report-images-service-{{ $dataInput->id }}')">
                                        <button type="button" onclick="confirmDeleteReportImage({{ $dataInput->id }}, {{ $image->id }})"
                                            class="absolute -top-1.5 -right-1.5 w-4 h-4 flex items-center justify-center text-[10px] text-white bg-red-500 rounded-full shadow hover:bg-red-600">
                                            ✕
                                        </button>
                                    </div>
                                @empty
                                    <span class="text-xs text-gray-400 italic">No images</span>
                                @endforelse
                            </div>
                            <button type="button" onclick="triggerReportUpload({{ $dataInput->id }}, 'service')"
                                class="w-full inline-flex items-center justify-center gap-1 px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 transition-colors whitespace-nowrap">
                                + Add
                            </button>
                        </div>
                    </td>
@endif
                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $dataInput->remark ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

    @if (!$isExport)
        <div class="mt-4">
            {{ $dataInputs->links() }}
        </div>
    @endif

    @if (!$isExport)

    {{-- Hidden file input for report gallery uploads --}}
    <input type="file" id="reportImageFileInput" accept="image/*" multiple class="hidden">

    {{-- Delete Image Confirmation Modal --}}
    <div id="reportDeleteConfirmModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center px-4">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-xs">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800">Delete Image?</p>
                    <p class="text-xs text-gray-500 mt-0.5">This cannot be undone.</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="cancelDeleteReportImage()"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 active:scale-95 transition-all">
                    Cancel
                </button>
                <button onclick="executeDeleteReportImage()"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 active:scale-95 transition-all">
                    Delete
                </button>
            </div>
        </div>
    </div>

    {{-- Image Preview Modal — with gallery navigation, same style as dashboard --}}
    <div id="reportImageModal" class="img-modal-overlay">
        <button type="button" onclick="closeReportImageModal()" class="img-modal-close-btn" aria-label="Close">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Close
        </button>

        <button type="button" id="reportImageModalPrev" onclick="showPrevReportImage(event)" class="img-modal-nav-btn img-modal-nav-left" aria-label="Previous image">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <img id="reportImageModalImg" src="" class="img-modal-image">

        <button type="button" id="reportImageModalNext" onclick="showNextReportImage(event)" class="img-modal-nav-btn img-modal-nav-right" aria-label="Next image">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div class="img-modal-footer">
            <span id="reportImageModalCounter" class="img-modal-counter"></span>
            <p class="img-modal-hint">Use ← → to navigate · Tap outside or press Esc to close</p>
        </div>
    </div>

    <style>
        .img-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 60;
            background: rgba(0, 0, 0, 0.92);
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .img-modal-overlay.is-open { display: flex; }
        .img-modal-image {
            max-width: 100%;
            max-height: 78vh;
            border-radius: 0.75rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            object-fit: contain;
            user-select: none;
        }
        .img-modal-close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 5;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            background: #fff;
            color: #1f2937;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            border-radius: 9999px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: background-color 0.15s, transform 0.1s;
        }
        .img-modal-close-btn:hover { background: #f3f4f6; }
        .img-modal-close-btn:active { transform: scale(0.95); }
        .img-modal-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            backdrop-filter: blur(4px);
            transition: background-color 0.15s, transform 0.1s;
        }
        .img-modal-nav-btn:hover { background: rgba(255, 255, 255, 0.25); }
        .img-modal-nav-btn:active { transform: translateY(-50%) scale(0.92); }
        .img-modal-nav-btn.is-hidden { display: none; }
        .img-modal-nav-left  { left: 0.75rem; }
        .img-modal-nav-right { right: 0.75rem; }
        @media (min-width: 640px) {
            .img-modal-nav-left  { left: 1.5rem; }
            .img-modal-nav-right { right: 1.5rem; }
            .img-modal-nav-btn { width: 3.5rem; height: 3.5rem; }
        }
        .img-modal-footer {
            margin-top: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }
        .img-modal-counter {
            display: none;
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            background: rgba(255, 255, 255, 0.12);
            padding: 0.125rem 0.625rem;
            border-radius: 9999px;
        }
        .img-modal-counter.is-visible { display: inline-block; }
        .img-modal-hint {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
            margin: 0;
        }
    </style>

    <script>
        // ── Report gallery: upload / delete / preview — mirrors the dashboard ──
        let reportCurrentUploadId   = null;
        let reportCurrentUploadType = null;
        let reportPendingDataInputId = null;
        let reportPendingImageId     = null;

        const reportFileInput = document.getElementById('reportImageFileInput');
        const reportCsrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function triggerReportUpload(id, type) {
            reportCurrentUploadId   = id;
            reportCurrentUploadType = type;
            reportFileInput.value   = '';
            reportFileInput.click();
        }

        reportFileInput.addEventListener('change', function () {
            if (!this.files.length) return;

            const formData = new FormData();
            for (const file of this.files) {
                formData.append('images[]', file);
            }

            fetch(`/data-inputs/${reportCurrentUploadId}/images/${reportCurrentUploadType}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': reportCsrfToken },
                body: formData,
            })
            .then(r => { if (!r.ok) throw new Error(); return r.json(); })
            .then(d => {
                const groupId = `report-images-${reportCurrentUploadType}-${reportCurrentUploadId}`;
                const container = document.getElementById(groupId);
                if (!container) return;
                container.querySelectorAll('span.italic').forEach(el => el.remove());
                d.images.forEach(img => {
                    container.insertAdjacentHTML('beforeend', buildReportImageThumb(reportCurrentUploadId, img.id, img.url, groupId));
                });
            })
            .catch(() => showReportToast('Upload failed. Please try again.', 'error'));
        });

        function buildReportImageThumb(dataInputId, imageId, url, groupId) {
            return `<div class="relative" id="report-image-${imageId}">
                <img src="${url}" class="w-12 h-12 object-cover rounded-lg cursor-pointer border border-gray-200 hover:opacity-80 transition-opacity" onclick="openReportImageModal('${url}', '${groupId}')">
                <button type="button" onclick="confirmDeleteReportImage(${dataInputId}, ${imageId})" class="absolute -top-1.5 -right-1.5 w-4 h-4 flex items-center justify-center text-[10px] text-white bg-red-500 rounded-full shadow hover:bg-red-600">✕</button>
            </div>`;
        }

        function confirmDeleteReportImage(dataInputId, imageId) {
            reportPendingDataInputId = dataInputId;
            reportPendingImageId     = imageId;
            document.getElementById('reportDeleteConfirmModal').classList.remove('hidden');
        }
        function cancelDeleteReportImage() {
            reportPendingDataInputId = reportPendingImageId = null;
            document.getElementById('reportDeleteConfirmModal').classList.add('hidden');
        }
        function executeDeleteReportImage() {
            const dataInputId = reportPendingDataInputId, imageId = reportPendingImageId;
            document.getElementById('reportDeleteConfirmModal').classList.add('hidden');
            if (!dataInputId || !imageId) return;

            fetch(`/data-inputs/${dataInputId}/images/${imageId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': reportCsrfToken },
            })
            .then(r => { if (!r.ok) throw new Error(); return r.json(); })
            .then(() => {
                document.querySelectorAll(`[id="report-image-${imageId}"]`).forEach(el => el.remove());
                showReportToast('Image deleted.');
            })
            .catch(() => showReportToast('Delete failed.', 'error'))
            .finally(() => { reportPendingDataInputId = reportPendingImageId = null; });
        }

        document.getElementById('reportDeleteConfirmModal').addEventListener('click', e => { if (e.target === e.currentTarget) cancelDeleteReportImage(); });

        // ── Gallery image modal (per type, same behavior as dashboard) ──
        let reportModalImages = [];
        let reportModalIndex  = 0;

        function openReportImageModal(src, groupId) {
            reportModalImages = [];

            const container = groupId ? document.getElementById(groupId) : null;
            if (container) {
                container.querySelectorAll('img').forEach(img => reportModalImages.push(img.getAttribute('src')));
            }
            if (!reportModalImages.length) {
                reportModalImages = [src];
            }

            reportModalIndex = reportModalImages.indexOf(src);
            if (reportModalIndex === -1) reportModalIndex = 0;

            updateReportModalImage();
            document.getElementById('reportImageModal').classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }

        function updateReportModalImage() {
            document.getElementById('reportImageModalImg').src = reportModalImages[reportModalIndex];

            const counter = document.getElementById('reportImageModalCounter');
            const prevBtn = document.getElementById('reportImageModalPrev');
            const nextBtn = document.getElementById('reportImageModalNext');

            if (reportModalImages.length > 1) {
                counter.textContent = `${reportModalIndex + 1} / ${reportModalImages.length}`;
                counter.classList.add('is-visible');
                prevBtn.classList.remove('is-hidden');
                nextBtn.classList.remove('is-hidden');
            } else {
                counter.classList.remove('is-visible');
                prevBtn.classList.add('is-hidden');
                nextBtn.classList.add('is-hidden');
            }
        }

        function showPrevReportImage(e) {
            if (e) e.stopPropagation();
            if (reportModalImages.length < 2) return;
            reportModalIndex = (reportModalIndex - 1 + reportModalImages.length) % reportModalImages.length;
            updateReportModalImage();
        }

        function showNextReportImage(e) {
            if (e) e.stopPropagation();
            if (reportModalImages.length < 2) return;
            reportModalIndex = (reportModalIndex + 1) % reportModalImages.length;
            updateReportModalImage();
        }

        function closeReportImageModal() {
            document.getElementById('reportImageModal').classList.remove('is-open');
            document.getElementById('reportImageModalImg').src = '';
            reportModalImages = [];
            reportModalIndex = 0;
            document.body.style.overflow = '';
        }

        document.getElementById('reportImageModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeReportImageModal();
        });

        document.addEventListener('keydown', e => {
            const modalOpen = document.getElementById('reportImageModal').classList.contains('is-open');
            if (modalOpen) {
                if (e.key === 'ArrowLeft') showPrevReportImage();
                if (e.key === 'ArrowRight') showNextReportImage();
                if (e.key === 'Escape') closeReportImageModal();
            }
            if (e.key === 'Escape') cancelDeleteReportImage();
        });

        function showReportToast(message, type = 'success') {
            const t = document.createElement('div');
            t.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-[9999] px-5 py-3 text-white text-sm font-semibold rounded-xl shadow-xl ${type === 'success' ? 'bg-emerald-500' : 'bg-red-500'} transition-all duration-300`;
            t.textContent = message;
            document.body.appendChild(t);
            setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translate(-50%, 8px)'; setTimeout(() => t.remove(), 300); }, 2800);
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('excel-ready', ({ url }) => {
                window.location.href = url;
            });

            Livewire.on('excel-error', ({ message }) => {
                alert(message);
            });
        });
    </script>
    @endif
</div>
