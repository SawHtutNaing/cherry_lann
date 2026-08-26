<x-app-layout>
<div class="container mx-auto mt-4 px-3 sm:px-4">

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9v4a1 1 0 102 0V9a1 1 0 10-2 0zm0-4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/></svg>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <h1 class="mb-4 text-xl sm:text-2xl font-bold text-gray-800">Data Inputs</h1>

    {{-- ── FILTER FORM ─────────────────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
        <div class="p-4 bg-white rounded-xl shadow-md border border-gray-100">

            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('data-inputs.create') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg shadow-sm hover:bg-blue-600 active:scale-95 transition-all">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Create New
                </a>
                @if(auth()->user()->role == 'admin')
                    <a href="{{ route('data-inputs.export-db') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-emerald-500 rounded-lg shadow-sm hover:bg-emerald-600 active:scale-95 transition-all">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export DB
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Service</label>
                    <select name="boosttype" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        @foreach ($boostTypes as $bt)
                            <option value="{{ $bt->id }}" @selected($boosttype == $bt->id)>{{ $bt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Status</label>
                    <select name="status_at" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        <option value="1" @selected($statusAt == '1')>Charge</option>
                        <option value="2" @selected($statusAt == '2')>Refund</option>
                        <option value="3" @selected($statusAt == '3')>Pending</option>
                        <option value="4" @selected($statusAt == '4')>Ongoing</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Customer</label>
                    <input type="text" name="cus_name_search" value="{{ $cusName }}" placeholder="Search..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Days ≥</label>
                    <input type="number" name="days_count" min="0" value="{{ $daysCount }}" placeholder="e.g. 7"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex flex-col justify-between gap-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer pt-5">
                        <input type="checkbox" name="check_remark" value="1" @checked($checkRemark)
                            class="w-4 h-4 rounded border-gray-300 accent-blue-500">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Remark</span>
                    </label>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 font-semibold text-sm text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 active:scale-95 transition-all">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4-2A1 1 0 018 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    <p class="mt-2 mb-1 text-xs text-gray-400">{{ $dataInputs->count() }} record(s) found</p>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- MOBILE CARD VIEW                                                       --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="mt-2 block sm:hidden space-y-3">
        @forelse ($dataInputs as $dataInput)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                {{-- Header --}}
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
                    <div class="min-w-0 flex-1 pr-2">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $dataInput->customer_name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $dataInput->page_name ?? 'N/A' }}</p>
                    </div>
                    <div class="shrink-0 flex flex-col items-end gap-1">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                            @if($dataInput->status->name == 'Charge') bg-green-100 text-green-700
                            @elseif($dataInput->status->name == 'Refund') bg-red-100 text-red-700
                            @else bg-amber-100 text-amber-700 @endif">
                            {{ $dataInput->status->label() }}
                        </span>
                        <span class="text-[10px] font-medium text-gray-400">{{ \Carbon\Carbon::parse($dataInput->created_at)->diffInDays(now()) }}d</span>
                    </div>
                </div>

                {{-- Line Items --}}
                <div class="px-4 py-3">
                    <div class="rounded-md border border-gray-200 divide-y divide-gray-100 overflow-hidden">
                        @forelse ($dataInput->items as $item)
                            <div class="px-2.5 py-1.5 bg-white text-[11px] leading-tight">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-semibold text-gray-800 truncate">{{ $item->boostType->name ?? 'N/A' }}</span>
                                    <span class="text-gray-400 shrink-0 tabular-nums">{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d/m/y') : 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-500 mt-0.5">
                                    <span>Qty {{ $item->amount }}</span>
                                    <span>·</span>
                                    <span>{{ number_format($item->mm_kyat) }}</span>
                                    <span>·</span>
                                    <span>-{{ number_format($item->discount) }}</span>
                                    <span class="ml-auto font-semibold text-gray-800">{{ number_format($item->line_total) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="px-2.5 py-2 text-xs text-gray-400 text-center">No items</div>
                        @endforelse
                    </div>
                </div>

                {{-- Image Gallery (mobile) --}}
                <div class="px-4 py-3 grid grid-cols-2 gap-2">
                    @foreach ([['type' => 'client', 'label' => 'Client'], ['type' => 'service', 'label' => 'Cherry Lann']] as $g)
                        <div class="bg-gray-50 rounded-lg p-2 border border-gray-100" id="gallery-{{ $g['type'] }}-{{ $dataInput->id }}">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5 text-center">{{ $g['label'] }}</p>

                            <div class="flex flex-wrap gap-1.5 justify-center mb-2" id="images-{{ $g['type'] }}-{{ $dataInput->id }}">
                                @forelse ($g['type'] === 'client' ? $dataInput->clientImages : $dataInput->serviceImages as $image)
                                    <div class="relative" id="image-{{ $image->id }}">
                                        <img src="{{ $image->url }}"
                                             class="w-14 h-14 object-cover rounded-lg cursor-pointer border border-gray-200 hover:opacity-80 transition"
                                             onclick="openImageModal('{{ $image->url }}', 'images-{{ $g['type'] }}-{{ $dataInput->id }}')">
                                        <button type="button" onclick="confirmDeleteImage({{ $dataInput->id }}, {{ $image->id }})"
                                            class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center text-xs text-white bg-red-500 rounded-full shadow hover:bg-red-600">
                                            ✕
                                        </button>
                                    </div>
                                @empty
                                    <span class="text-xs text-gray-400 italic">No images</span>
                                @endforelse
                            </div>

                            <button type="button" onclick="triggerUpload({{ $dataInput->id }}, '{{ $g['type'] }}')"
                                class="w-full inline-flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600 active:scale-95 transition-all">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Add
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- ── Action buttons — 2×2 grid, all perfectly equal ─────────── --}}
                <div class="px-4 pb-4 grid grid-cols-2 gap-2">

                    <a href="{{ route('data-inputs.edit', $dataInput->id) }}"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-xs font-semibold text-white bg-amber-500 rounded-lg shadow-sm hover:bg-amber-600 active:scale-95 transition-all">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>

                    <a href="{{ route('data-inputs.export', $dataInput->id) }}"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-xs font-semibold text-white bg-blue-500 rounded-lg shadow-sm hover:bg-blue-600 active:scale-95 transition-all">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 11v6m-3-3l3 3 3-3"/></svg>
                        Export
                    </a>

                    <form method="POST" action="{{ route('data-inputs.copy', $dataInput->id) }}"
                          onsubmit="return confirm('Are you sure you want to Copy?')">
                        @csrf
                        @foreach(request()->query() as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-xs font-semibold text-white bg-emerald-500 rounded-lg shadow-sm hover:bg-emerald-600 active:scale-95 transition-all">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Copy
                        </button>
                    </form>

                    <form method="POST" action="{{ route('data-inputs.delete', $dataInput->id) }}"
                          onsubmit="return confirm('Are you sure you want to Delete?')">
                        @csrf
                        @method('DELETE')
                        @foreach(request()->query() as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-xs font-semibold text-white bg-red-500 rounded-lg shadow-sm hover:bg-red-600 active:scale-95 transition-all">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 01-1-1V5a1 1 0 011-1h8a1 1 0 011 1v1a1 1 0 01-1 1H9z"/></svg>
                            Delete
                        </button>
                    </form>

                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm font-medium">No records found</p>
                <p class="text-xs mt-1">Try adjusting your filters</p>
            </div>
        @endforelse
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- DESKTOP TABLE VIEW                                                     --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="mt-4 overflow-x-auto overflow-y-auto h-[58vh] relative hidden sm:block rounded-xl border border-gray-200 shadow-sm">
        <table class="min-w-[1770px] w-full bg-white table-fixed">
            <thead class="sticky top-0 bg-gray-50 z-10 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[50px]">No</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[170px]">Action</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[140px]">Customer</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[140px]">Page Name</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[110px]">Phone</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[220px]">Items</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[110px]">Total</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[70px]">Days</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[90px]">Status</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[130px]">Client Img</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[130px]">Cherry Lann</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[80px]">Remark</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[110px]">Created</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[110px]">Updated</th>
                    <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase tracking-wide w-[80px]">Export</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($dataInputs as $dataInput)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('data-inputs.edit', $dataInput->id) }}"
                                    class="inline-flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-md hover:bg-amber-600 transition-colors">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('data-inputs.copy', $dataInput->id) }}"
                                      onsubmit="return confirm('Are you sure you want to Copy?')">
                                    @csrf
                                    @foreach(request()->query() as $k => $v)
                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endforeach
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-emerald-500 rounded-md hover:bg-emerald-600 transition-colors">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        Copy
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('data-inputs.delete', $dataInput->id) }}"
                                      onsubmit="return confirm('Are you sure you want to Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    @foreach(request()->query() as $k => $v)
                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endforeach
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-colors">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 01-1-1V5a1 1 0 011-1h8a1 1 0 011 1v1a1 1 0 01-1 1H9z"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $dataInput->customer_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $dataInput->page_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $dataInput->phone ?? 'N/A' }}</td>

                        {{-- Items summary --}}
                        <td class="px-4 py-3 text-xs text-gray-600 whitespace-normal">
                            @forelse ($dataInput->items as $item)
                                <div class="mb-1 last:mb-0 leading-tight">
                                    <span class="font-semibold text-gray-800">{{ $item->boostType->name ?? 'N/A' }}</span>
                                    — {{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d/m/y') : 'N/A' }},
                                    Qty {{ $item->amount }}, Amt {{ number_format($item->mm_kyat) }},
                                    Disc {{ number_format($item->discount) }},
                                    <span class="font-semibold">Ln {{ number_format($item->line_total) }}</span>
                                </div>
                            @empty
                                <span class="text-gray-400">No items</span>
                            @endforelse
                        </td>

                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ number_format($dataInput->total_amount) }}</td>

                        {{-- Days since created --}}
                       {{-- Days since created (colored to match Status) --}}
<td class="px-4 py-3 text-sm">
    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
        @if($dataInput->status->name == 'Charge') bg-green-100 text-green-700
        @elseif($dataInput->status->name == 'Refund') bg-red-100 text-red-700
        @else bg-amber-100 text-amber-700 @endif">
        {{ \Carbon\Carbon::parse($dataInput->created_at)->diffInDays(now()) }}d
    </span>
</td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                @if($dataInput->status->name == 'Charge') bg-green-100 text-green-700
                                @elseif($dataInput->status->name == 'Refund') bg-red-100 text-red-700
                                @else bg-amber-100 text-amber-700 @endif">
                                {{ $dataInput->status->label() }}
                            </span>
                        </td>

                        {{-- Client Images --}}
                        <td class="px-4 py-3">
                            <div id="gallery-client-{{ $dataInput->id }}">
                                <div class="flex flex-wrap gap-1 justify-center mb-1.5" id="images-client-{{ $dataInput->id }}">
                                    @forelse ($dataInput->clientImages as $image)
                                        <div class="relative" id="image-{{ $image->id }}">
                                            <img src="{{ $image->url }}"
                                                 class="w-12 h-12 object-cover rounded-lg cursor-pointer border border-gray-200 hover:opacity-80 transition-opacity"
                                                 onclick="openImageModal('{{ $image->url }}', 'images-client-{{ $dataInput->id }}')">
                                            <button type="button" onclick="confirmDeleteImage({{ $dataInput->id }}, {{ $image->id }})"
                                                class="absolute -top-1.5 -right-1.5 w-4 h-4 flex items-center justify-center text-[10px] text-white bg-red-500 rounded-full shadow hover:bg-red-600">
                                                ✕
                                            </button>
                                        </div>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">No images</span>
                                    @endforelse
                                </div>
                                <button type="button" onclick="triggerUpload({{ $dataInput->id }}, 'client')"
                                    class="w-full inline-flex items-center justify-center gap-1 px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 transition-colors whitespace-nowrap">
                                    + Add
                                </button>
                            </div>
                        </td>

                        {{-- Service Images --}}
                        <td class="px-4 py-3">
                            <div id="gallery-service-{{ $dataInput->id }}">
                                <div class="flex flex-wrap gap-1 justify-center mb-1.5" id="images-service-{{ $dataInput->id }}">
                                    @forelse ($dataInput->serviceImages as $image)
                                        <div class="relative" id="image-{{ $image->id }}">
                                            <img src="{{ $image->url }}"
                                                 class="w-12 h-12 object-cover rounded-lg cursor-pointer border border-gray-200 hover:opacity-80 transition-opacity"
                                                 onclick="openImageModal('{{ $image->url }}', 'images-service-{{ $dataInput->id }}')">
                                            <button type="button" onclick="confirmDeleteImage({{ $dataInput->id }}, {{ $image->id }})"
                                                class="absolute -top-1.5 -right-1.5 w-4 h-4 flex items-center justify-center text-[10px] text-white bg-red-500 rounded-full shadow hover:bg-red-600">
                                                ✕
                                            </button>
                                        </div>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">No images</span>
                                    @endforelse
                                </div>
                                <button type="button" onclick="triggerUpload({{ $dataInput->id }}, 'service')"
                                    class="w-full inline-flex items-center justify-center gap-1 px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 transition-colors whitespace-nowrap">
                                    + Add
                                </button>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if($dataInput->is_remark)
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-green-100 text-green-600 rounded-full text-xs font-bold">✓</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ \Carbon\Carbon::parse($dataInput->created_at)->format('d/m/y H:i') }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ \Carbon\Carbon::parse($dataInput->updated_at)->format('d/m/y H:i') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('data-inputs.export', $dataInput->id) }}"
                               class="inline-flex items-center justify-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 transition-colors">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 11v6m-3-3l3 3 3-3"/></svg>
                                PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="text-center text-gray-400 py-16">
                            <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="text-sm font-medium">No records found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Hidden file input (multiple selection enabled) --}}
    <input type="file" id="imageFileInput" accept="image/*" multiple class="hidden">

    {{-- Delete Image Confirmation Modal --}}
    <div id="deleteConfirmModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center px-4">
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
                <button onclick="cancelDeleteImage()"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 active:scale-95 transition-all">
                    Cancel
                </button>
                <button onclick="executeDeleteImage()"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 active:scale-95 transition-all">
                    Delete
                </button>
            </div>
        </div>
    </div>

    {{-- Image Preview Modal — with gallery navigation --}}
    <div id="imageModal" class="img-modal-overlay">
        <button type="button" onclick="closeImageModal()" class="img-modal-close-btn" aria-label="Close">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Close
        </button>

        <button type="button" id="imageModalPrev" onclick="showPrevImage(event)" class="img-modal-nav-btn img-modal-nav-left" aria-label="Previous image">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <img id="imageModalImg" src="" class="img-modal-image">

        <button type="button" id="imageModalNext" onclick="showNextImage(event)" class="img-modal-nav-btn img-modal-nav-right" aria-label="Next image">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div class="img-modal-footer">
            <span id="imageModalCounter" class="img-modal-counter"></span>
            <p class="img-modal-hint">Use ← → to navigate · Tap outside or press Esc to close</p>
        </div>
    </div>

</div>

<style>
    /* ── Gallery Image Modal — vanilla CSS ─────────────────────────────── */
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
    .img-modal-overlay.is-open {
        display: flex;
    }
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
    let currentUploadId    = null;
    let currentUploadType  = null;
    let pendingDataInputId = null;
    let pendingImageId     = null;

    const fileInput = document.getElementById('imageFileInput');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function triggerUpload(id, type) {
        currentUploadId   = id;
        currentUploadType = type;
        fileInput.value   = '';
        fileInput.click();
    }

    fileInput.addEventListener('change', function () {
        if (!this.files.length) return;

        const formData = new FormData();
        for (const file of this.files) {
            formData.append('images[]', file);
        }

        fetch(`/data-inputs/${currentUploadId}/images/${currentUploadType}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData,
        })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(d => {
            const groupId = `images-${currentUploadType}-${currentUploadId}`;
            document.querySelectorAll(`[id="${groupId}"]`).forEach(container => {
                container.querySelectorAll('span.italic').forEach(el => el.remove());
                d.images.forEach(img => {
                    container.insertAdjacentHTML('beforeend', buildImageThumb(currentUploadId, img.id, img.url, groupId));
                });
            });
        })
        .catch(() => showToast('Upload failed. Please try again.', 'error'));
    });

    function buildImageThumb(dataInputId, imageId, url, groupId) {
        return `<div class="relative" id="image-${imageId}">
            <img src="${url}" class="w-12 h-12 object-cover rounded-lg cursor-pointer border border-gray-200 hover:opacity-80 transition-opacity" onclick="openImageModal('${url}', '${groupId}')">
            <button type="button" onclick="confirmDeleteImage(${dataInputId}, ${imageId})" class="absolute -top-1.5 -right-1.5 w-4 h-4 flex items-center justify-center text-[10px] text-white bg-red-500 rounded-full shadow hover:bg-red-600">✕</button>
        </div>`;
    }

    function confirmDeleteImage(dataInputId, imageId) {
        pendingDataInputId = dataInputId;
        pendingImageId      = imageId;
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
    }
    function cancelDeleteImage() {
        pendingDataInputId = pendingImageId = null;
        document.getElementById('deleteConfirmModal').classList.add('hidden');
    }
    function executeDeleteImage() {
        const dataInputId = pendingDataInputId, imageId = pendingImageId;
        document.getElementById('deleteConfirmModal').classList.add('hidden');
        if (!dataInputId || !imageId) return;

        fetch(`/data-inputs/${dataInputId}/images/${imageId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken },
        })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(() => {
            document.querySelectorAll(`[id="image-${imageId}"]`).forEach(el => el.remove());
            showToast('Image deleted.');
        })
        .catch(() => showToast('Delete failed.', 'error'))
        .finally(() => { pendingDataInputId = pendingImageId = null; });
    }

    document.getElementById('deleteConfirmModal').addEventListener('click', e => { if (e.target === e.currentTarget) cancelDeleteImage(); });

    // ── Gallery image modal (vanilla JS) ───────────────────────────────
    let modalImages = [];
    let modalIndex  = 0;

    function openImageModal(src, groupId) {
        modalImages = [];

        const container = groupId ? document.getElementById(groupId) : null;
        if (container) {
            container.querySelectorAll('img').forEach(img => modalImages.push(img.getAttribute('src')));
        }
        if (!modalImages.length) {
            modalImages = [src];
        }

        modalIndex = modalImages.indexOf(src);
        if (modalIndex === -1) modalIndex = 0;

        updateModalImage();
        document.getElementById('imageModal').classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function updateModalImage() {
        document.getElementById('imageModalImg').src = modalImages[modalIndex];

        const counter = document.getElementById('imageModalCounter');
        const prevBtn = document.getElementById('imageModalPrev');
        const nextBtn = document.getElementById('imageModalNext');

        if (modalImages.length > 1) {
            counter.textContent = `${modalIndex + 1} / ${modalImages.length}`;
            counter.classList.add('is-visible');
            prevBtn.classList.remove('is-hidden');
            nextBtn.classList.remove('is-hidden');
        } else {
            counter.classList.remove('is-visible');
            prevBtn.classList.add('is-hidden');
            nextBtn.classList.add('is-hidden');
        }
    }

    function showPrevImage(e) {
        if (e) e.stopPropagation();
        if (modalImages.length < 2) return;
        modalIndex = (modalIndex - 1 + modalImages.length) % modalImages.length;
        updateModalImage();
    }

    function showNextImage(e) {
        if (e) e.stopPropagation();
        if (modalImages.length < 2) return;
        modalIndex = (modalIndex + 1) % modalImages.length;
        updateModalImage();
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.remove('is-open');
        document.getElementById('imageModalImg').src = '';
        modalImages = [];
        modalIndex = 0;
        document.body.style.overflow = '';
    }

    document.getElementById('imageModal').addEventListener('click', e => {
        if (e.target === e.currentTarget) closeImageModal();
    });

    document.addEventListener('keydown', e => {
        const modalOpen = document.getElementById('imageModal').classList.contains('is-open');

        if (modalOpen) {
            if (e.key === 'ArrowLeft') showPrevImage();
            if (e.key === 'ArrowRight') showNextImage();
            if (e.key === 'Escape') closeImageModal();
        }

        if (e.key === 'Escape') cancelDeleteImage();
    });

    function showToast(message, type = 'success') {
        const t = document.createElement('div');
        t.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-[9999] px-5 py-3 text-white text-sm font-semibold rounded-xl shadow-xl ${type === 'success' ? 'bg-emerald-500' : 'bg-red-500'} transition-all duration-300`;
        t.textContent = message;
        document.body.appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translate(-50%, 8px)'; setTimeout(() => t.remove(), 300); }, 2800);
    }
</script>
</x-app-layout>
