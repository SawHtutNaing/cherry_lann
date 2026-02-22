
    <div class="container mx-auto mt-4 px-3 sm:px-4">
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <h1 class="mb-4 text-xl sm:text-2xl font-semibold">Data Inputs</h1>

        <div class="p-4 sm:p-6 bg-white rounded-lg shadow-lg">
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('data-inputs.create') }}"
                    class="inline-block px-4 py-2 text-sm text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                    + Create New
                </a>
                @if(auth()->user()->role == 'admin')
                    <button wire:click="exportDatabase"
                        class="inline-block px-4 py-2 text-sm text-white bg-green-500 rounded shadow hover:bg-green-400">
                        Export DB
                    </button>
                @endif
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <div class="w-full sm:w-[48%] lg:w-1/4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Type</label>
                    <select wire:model="boosttype"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        @foreach ($boostTypes as $boostType)
                            <option value="{{ $boostType->id }}">{{ $boostType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-[48%] lg:w-1/4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model="status_at"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        <option value="1">Charge</option>
                        <option value="2">Refund</option>
                        <option value="3">Pending</option>
                    </select>
                </div>

                <div class="w-full sm:w-[48%] lg:w-1/4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" wire:model='startDate'
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="w-full sm:w-[48%] lg:w-1/4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" wire:model='endDate'
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="w-full sm:w-[48%] lg:w-1/4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Search</label>
                    <input type="text" wire:model='cus_name_search' placeholder="Search name..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="w-full sm:w-[48%] lg:w-1/4 flex items-center gap-3 pt-2 sm:pt-6">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 cursor-pointer">
                        <input type="checkbox" wire:model='check_remark'
                            class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500">
                        Only Remark
                    </label>
                </div>

                <div class="w-full sm:w-auto flex items-end">
                    <button wire:click='filterData()'
                        class="w-full sm:w-auto px-5 py-2 font-semibold text-sm text-white bg-blue-600 rounded-md shadow hover:bg-blue-700">
                        Filter
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Card View (shown on small screens) --}}
        <div class="mt-6 block sm:hidden space-y-4">
            @foreach ($dataInputs as $dataInput)
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $dataInput->customer_name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $dataInput->page_name ?? 'N/A' }}</p>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full
                            @if($dataInput->status->name == 'Charge') bg-green-100 text-green-700
                            @elseif($dataInput->status->name == 'Refund') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $dataInput->status->label() }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-1 text-sm text-gray-600 mb-3">
                        <span class="font-medium">Phone:</span>
                        <span>{{ $dataInput->phone ?? 'N/A' }}</span>
                        <span class="font-medium">Service:</span>
                        <span>{{ $dataInput->boostType->name ?? 'N/A' }}</span>
                        <span class="font-medium">Date:</span>
                        <span>{{ $dataInput->start_date ? \Carbon\Carbon::parse($dataInput->start_date)->format('d/m/y') : 'N/A' }}</span>
                        <span class="font-medium">Qty:</span>
                        <span>{{ $dataInput->amount ?? 'N/A' }}</span>
                        <span class="font-medium">Amount:</span>
                        <span>{{ $dataInput->mm_kyat }}</span>
                        <span class="font-medium">Discount:</span>
                        <span>{{ $dataInput->discount }}</span>
                        <span class="font-medium">Total:</span>
                        <span>{{ $dataInput->total_amount }}</span>
                        @if($dataInput->is_remark)
                            <span class="font-medium">Remark:</span>
                            <span>✅</span>
                        @endif
                    </div>

                    {{-- Mobile Image Upload Section --}}
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <p class="text-xs font-medium text-gray-600 mb-1">Client Image</p>
                            <div id="cell-client-{{ $dataInput->id }}">
                                @if($dataInput->client_side_image)
                                    <div class="flex flex-col gap-1">
                                        <img src="{{ Storage::disk('public')->url($dataInput->client_side_image) }}"
                                             class="w-full h-24 object-cover rounded cursor-pointer"
                                             onclick="openImageModal(this.src)">
                                        <button onclick="confirmDeleteImage({{ $dataInput->id }}, 'client_side_image')"
                                                class="w-full px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-400">
                                            🗑 Delete
                                        </button>
                                    </div>
                                @else
                                    <button onclick="triggerUpload({{ $dataInput->id }}, 'client_side_image')"
                                            class="w-full px-2 py-2 text-xs text-white bg-blue-500 rounded hover:bg-blue-400">
                                        📷 Upload
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-600 mb-1">Cherry Lann Image</p>
                            <div id="cell-service-{{ $dataInput->id }}">
                                @if($dataInput->service_side_image)
                                    <div class="flex flex-col gap-1">
                                        <img src="{{ Storage::disk('public')->url($dataInput->service_side_image) }}"
                                             class="w-full h-24 object-cover rounded cursor-pointer"
                                             onclick="openImageModal(this.src)">
                                        <button onclick="confirmDeleteImage({{ $dataInput->id }}, 'service_side_image')"
                                                class="w-full px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-400">
                                            🗑 Delete
                                        </button>
                                    </div>
                                @else
                                    <button onclick="triggerUpload({{ $dataInput->id }}, 'service_side_image')"
                                            class="w-full px-2 py-2 text-xs text-white bg-blue-500 rounded hover:bg-blue-400">
                                        📷 Upload
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Mobile Actions --}}
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('data-inputs.edit', $dataInput->id) }}"
                            class="flex-1 text-center px-3 py-2 text-xs text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">
                            ✏️ Edit
                        </a>
                        <button wire:confirm='Are You Sure Want To Copy ?'
                            wire:click="copy({{ $dataInput->id }})"
                            class="flex-1 px-3 py-2 text-xs text-white bg-green-500 rounded shadow hover:bg-green-400">
                            📋 Copy
                        </button>
                        <button class="flex-1 px-3 py-2 text-xs text-white bg-blue-500 rounded shadow hover:bg-blue-400"
                            wire:click='export({{ $dataInput->id }})'>
                            📄 Export
                        </button>
                        <button wire:confirm='Are You Sure Want To Delete ?'
                            wire:click="delete({{ $dataInput->id }})"
                            class="flex-1 px-3 py-2 text-xs text-white bg-red-500 rounded shadow hover:bg-red-400">
                            🗑 Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Desktop Table View (hidden on small screens) --}}
        <div class="mt-6 overflow-x-auto overflow-y-auto h-[55vh] relative hidden sm:block">
            <table class="min-w-[1700px] w-full bg-white border border-gray-200 table-fixed">
                <thead class="sticky top-0 bg-gray-100 z-10">
                    <tr class="border-b">
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[50px]">No</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[170px]">Action</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[140px]">Customer Name</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[140px]">Page Name</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[110px]">Phone</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[110px]">Service Type</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[90px]">Start Date</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[70px]">Qty</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[90px]">Amount</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[90px]">Discount</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[110px]">Total Amount</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[90px]">Status</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[130px]">Client Image</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[130px]">Cherry Lann Image</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[90px]">Remark</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[110px]">Created At</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[110px]">Updated At</th>
                        <th class="px-4 py-3 text-sm font-medium text-left text-gray-600 w-[90px]">Export</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataInputs as $dataInput)
                        <tr class="border-b hover:bg-gray-50" wire:key='{{ $dataInput->id }}'>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('data-inputs.edit', $dataInput->id) }}"
                                        class="px-3 text-center py-1.5 text-xs text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">Edit</a>
                                    <button wire:confirm='Are You Sure Want To Copy ?'
                                        wire:click="copy({{ $dataInput->id }})"
                                        class="px-3 py-1.5 text-xs text-white bg-green-500 rounded shadow hover:bg-green-400">Copy</button>
                                    <button wire:confirm='Are You Sure Want To Delete ?'
                                        wire:click="delete({{ $dataInput->id }})"
                                        class="px-3 py-1.5 text-xs text-white bg-red-500 rounded shadow hover:bg-red-400">Delete</button>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $dataInput->customer_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $dataInput->page_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $dataInput->phone ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $dataInput->boostType->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ $dataInput->start_date ? \Carbon\Carbon::parse($dataInput->start_date)->format('d/m/y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $dataInput->amount ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $dataInput->mm_kyat }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $dataInput->discount }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $dataInput->total_amount }}</td>
                            <td class="px-4 py-3 text-sm font-medium
                                @if($dataInput->status->name == 'Charge') text-green-600
                                @elseif($dataInput->status->name == 'Refund') text-red-600
                                @else text-yellow-600 @endif">
                                {{ $dataInput->status->label() }}
                            </td>

                            {{-- Client Image --}}
                            <td class="px-4 py-3 text-sm text-gray-800">
                                <div id="cell-client-{{ $dataInput->id }}">
                                    @if($dataInput->client_side_image)
                                        <div class="flex flex-col gap-1 items-center">
                                            <img src="{{ Storage::disk('public')->url($dataInput->client_side_image) }}"
                                                 class="w-16 h-16 object-cover rounded cursor-pointer border border-gray-200"
                                                 onclick="openImageModal(this.src)">
                                            <button onclick="confirmDeleteImage({{ $dataInput->id }}, 'client_side_image')"
                                                    class="px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-400">
                                                Delete
                                            </button>
                                        </div>
                                    @else
                                        <button onclick="triggerUpload({{ $dataInput->id }}, 'client_side_image')"
                                                class="px-2 py-1.5 text-xs text-white bg-blue-500 rounded hover:bg-blue-400 whitespace-nowrap">
                                            📷 Upload
                                        </button>
                                    @endif
                                </div>
                            </td>

                            {{-- Cherry Lann / Service Image --}}
                            <td class="px-4 py-3 text-sm text-gray-800">
                                <div id="cell-service-{{ $dataInput->id }}">
                                    @if($dataInput->service_side_image)
                                        <div class="flex flex-col gap-1 items-center">
                                            <img src="{{ Storage::disk('public')->url($dataInput->service_side_image) }}"
                                                 class="w-16 h-16 object-cover rounded cursor-pointer border border-gray-200"
                                                 onclick="openImageModal(this.src)">
                                            <button onclick="confirmDeleteImage({{ $dataInput->id }}, 'service_side_image')"
                                                    class="px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-400">
                                                Delete
                                            </button>
                                        </div>
                                    @else
                                        <button onclick="triggerUpload({{ $dataInput->id }}, 'service_side_image')"
                                                class="px-2 py-1.5 text-xs text-white bg-blue-500 rounded hover:bg-blue-400 whitespace-nowrap">
                                            📷 Upload
                                        </button>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-800">{{ $dataInput->is_remark == 1 ? '✅' : '' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($dataInput->created_at)->format('d/m/y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($dataInput->updated_at)->format('d/m/y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button class="px-3 py-1.5 text-xs text-white bg-blue-500 rounded shadow hover:bg-blue-400"
                                    wire:click='export({{ $dataInput->id }})'>
                                    Export
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Hidden file input — capture="" allows camera on mobile --}}
        <input type="file" id="imageFileInput" accept="image/*" class="hidden">

        {{-- Delete Confirmation Modal --}}
        <div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center px-4">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Delete Image</h3>
                <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this image? This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button onclick="cancelDeleteImage()"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <button onclick="executeDeleteImage()"
                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600">
                        Delete
                    </button>
                </div>
            </div>
        </div>

        {{-- Image Preview Modal --}}
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex flex-col items-center justify-center px-4">
            {{-- Cancel / Close button --}}
            <button onclick="closeImageModal()"
                    class="absolute top-4 right-4 z-10 flex items-center gap-1 px-4 py-2 bg-white text-gray-800 text-sm font-medium rounded-full shadow-lg hover:bg-gray-100 active:scale-95 transition">
                ✕ Close
            </button>
            <img id="imageModalImg" src="" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl object-contain">
            <p class="mt-3 text-white text-xs opacity-60">Tap outside or press Close to dismiss</p>
        </div>

    </div>

    <script>
        let currentUploadId = null;
        let currentUploadType = null;
        let pendingDeleteId = null;
        let pendingDeleteType = null;

        const fileInput = document.getElementById('imageFileInput');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ── Upload ──────────────────────────────────────────────────────────────
        function triggerUpload(id, type) {
            currentUploadId = id;
            currentUploadType = type;
            fileInput.value = '';
            fileInput.click();
        }

        fileInput.addEventListener('change', function () {
            if (!this.files.length) return;

            const file = this.files[0];
            const formData = new FormData();
            formData.append('image', file);

            const prefix = currentUploadType === 'client_side_image' ? 'client' : 'service';
            // Update ALL matching cells (mobile + desktop both have same id)
            setCellHtml(currentUploadId, currentUploadType,
                `<div class="flex flex-col gap-1 items-center">
                    <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                        <svg class="animate-spin w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-gray-400">Uploading...</span>
                </div>`
            );

            fetch(`/data-inputs/${currentUploadId}/image/${currentUploadType}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData,
            })
            .then(res => {
                if (!res.ok) throw new Error('Upload failed');
                return res.json();
            })
            .then(data => {
                setCellHtml(currentUploadId, currentUploadType, buildImageCell(currentUploadId, currentUploadType, data.url));
            })
            .catch(() => {
                setCellHtml(currentUploadId, currentUploadType, buildUploadBtn(currentUploadId, currentUploadType));
                showToast('Upload failed. Please try again.', 'error');
            });
        });

        // ── Delete (with custom confirmation modal) ─────────────────────────────
        function confirmDeleteImage(id, type) {
            pendingDeleteId = id;
            pendingDeleteType = type;
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
        }

        function cancelDeleteImage() {
            pendingDeleteId = null;
            pendingDeleteType = null;
            document.getElementById('deleteConfirmModal').classList.add('hidden');
        }

        function executeDeleteImage() {
            const id = pendingDeleteId;
            const type = pendingDeleteType;
            document.getElementById('deleteConfirmModal').classList.add('hidden');

            if (!id || !type) return;

            setCellHtml(id, type, '<span class="text-xs text-gray-400">Deleting...</span>');

            fetch(`/data-inputs/${id}/image/${type}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            })
            .then(res => {
                if (!res.ok) throw new Error('Delete failed');
                return res.json();
            })
            .then(() => {
                setCellHtml(id, type, buildUploadBtn(id, type));
                showToast('Image deleted successfully.', 'success');
            })
            .catch(() => {
                showToast('Delete failed. Please try again.', 'error');
            })
            .finally(() => {
                pendingDeleteId = null;
                pendingDeleteType = null;
            });
        }

        // Close delete modal if clicking backdrop
        document.getElementById('deleteConfirmModal').addEventListener('click', function (e) {
            if (e.target === this) cancelDeleteImage();
        });

        // ── Helpers ─────────────────────────────────────────────────────────────
        function setCellHtml(id, type, html) {
            const prefix = type === 'client_side_image' ? 'client' : 'service';
            // There can be two elements with same id (mobile + desktop), update all
            document.querySelectorAll(`[id="cell-${prefix}-${id}"]`).forEach(el => {
                el.innerHTML = html;
            });
        }

        function buildImageCell(id, type, url) {
            return `
                <div class="flex flex-col gap-1 items-center">
                    <img src="${url}" class="w-16 h-16 object-cover rounded cursor-pointer border border-gray-200"
                         onclick="openImageModal('${url}')">
                    <button onclick="confirmDeleteImage(${id}, '${type}')"
                            class="px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-400">
                        Delete
                    </button>
                </div>`;
        }

        function buildUploadBtn(id, type) {
            return `<button onclick="triggerUpload(${id}, '${type}')"
                            class="px-2 py-1.5 text-xs text-white bg-blue-500 rounded hover:bg-blue-400 whitespace-nowrap">
                        📷 Upload
                    </button>`;
        }

        // ── Image Preview Modal ──────────────────────────────────────────────────
        function openImageModal(src) {
            document.getElementById('imageModalImg').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModalImg').src = '';
            document.body.style.overflow = '';
        }

        // Close image modal on backdrop click
        document.getElementById('imageModal').addEventListener('click', function (e) {
            if (e.target === this) closeImageModal();
        });

        // Close modals on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeImageModal();
                cancelDeleteImage();
            }
        });

        // ── Toast Notification ───────────────────────────────────────────────────
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bg = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            toast.className = `fixed bottom-5 left-1/2 -translate-x-1/2 z-[9999] px-5 py-3 text-white text-sm rounded-lg shadow-lg ${bg} transition-all duration-300`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</div>
