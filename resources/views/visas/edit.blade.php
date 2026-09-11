<x-app-layout>
<div class="max-w-xl p-6 mx-auto mt-8 bg-white rounded-md shadow-md">
    <h1 class="mb-6 text-2xl font-semibold">Edit Visa</h1>

    @if ($errors->any())
        <div class="p-4 mb-4 text-red-800 bg-red-100 rounded">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('visas.update', $visa->id) }}" enctype="multipart/form-data" id="visaEditForm">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700">Amount:</label>
            <input type="number" name="amount" value="{{ old('amount', $visa->amount) }}" min="0" step="1"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Date:</label>
            <input type="date" name="date"
                value="{{ old('date', optional($visa->date)->format('Y-m-d')) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Remark:</label>
            <textarea name="remark" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('remark', $visa->remark) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Existing Images:</label>
            <div id="visaImagesGrid" class="grid grid-cols-3 gap-3">
                @forelse ($visa->images as $image)
                    <div class="relative" id="visa-image-{{ $image->id }}">
                        <img src="{{ $image->url }}" class="w-full h-24 object-cover rounded border border-gray-200">
                        <button type="button" onclick="deleteVisaImage({{ $visa->id }}, {{ $image->id }})"
                            class="absolute top-1 right-1 px-2 py-0.5 text-xs text-white bg-red-500 rounded shadow hover:bg-red-600">
                            ✕
                        </button>
                    </div>
                @empty
                    <span class="text-sm text-gray-400 col-span-3">No images yet.</span>
                @endforelse
            </div>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-gray-700">Add More Images:</label>
            <div id="newImageSlots" class="flex flex-wrap gap-3"></div>
            <button type="button" id="addImageBtn"
                class="px-3 py-1.5 mt-3 text-sm text-blue-600 border border-blue-300 rounded-md hover:bg-blue-50">
                + Add Image
            </button>
            <p class="mt-1 text-xs text-gray-400">Add one image at a time — each slot has its own Edit / Delete.</p>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('visas.index') }}"
                class="px-4 py-2 text-white bg-gray-500 rounded-md shadow hover:bg-gray-400">Cancel</a>
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md shadow hover:bg-blue-400">Update</button>
        </div>
    </form>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function deleteVisaImage(visaId, imageId) {
        if (!confirm('Delete this image?')) return;

        fetch(`/visas/${visaId}/images/${imageId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken },
        })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(() => {
            document.getElementById(`visa-image-${imageId}`).remove();
        })
        .catch(() => alert('Failed to delete image.'));
    }

    (function () {
        const slotsContainer = document.getElementById('newImageSlots');
        const addBtn = document.getElementById('addImageBtn');
        const form = document.getElementById('visaEditForm');

        function createImageSlot() {
            const slot = document.createElement('div');
            slot.className = 'relative flex flex-col items-center justify-center overflow-hidden bg-gray-50 border border-gray-300 border-dashed rounded w-24 h-24 shrink-0';
            slot.innerHTML = `
                <input type="file" name="images[]" accept="image/*" class="hidden" data-file-input>
                <img class="absolute inset-0 hidden object-cover w-full h-full" data-preview>
                <button type="button" class="flex flex-col items-center gap-1 text-gray-400" data-choose>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span class="text-xs">Choose</span>
                </button>
                <div class="absolute inset-x-0 bottom-0 items-center justify-center hidden gap-3 py-1 text-xs bg-black bg-opacity-60" data-actions>
                    <button type="button" class="text-white hover:underline" data-edit>Edit</button>
                    <button type="button" class="text-red-300 hover:underline" data-delete>Delete</button>
                </div>
            `;

            const fileInput = slot.querySelector('[data-file-input]');
            const preview = slot.querySelector('[data-preview]');
            const chooseBtn = slot.querySelector('[data-choose]');
            const actions = slot.querySelector('[data-actions]');
            const editBtn = slot.querySelector('[data-edit]');
            const deleteBtn = slot.querySelector('[data-delete]');

            chooseBtn.addEventListener('click', () => fileInput.click());
            editBtn.addEventListener('click', () => fileInput.click());
            deleteBtn.addEventListener('click', () => slot.remove());

            fileInput.addEventListener('change', () => {
                const file = fileInput.files[0];
                if (!file) return;

                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
                chooseBtn.classList.add('hidden');
                actions.classList.remove('hidden');
                actions.classList.add('flex');
            });

            return slot;
        }

        addBtn.addEventListener('click', () => slotsContainer.appendChild(createImageSlot()));

        // Slots added but left empty (e.g. an unused extra one) must not be submitted —
        // an empty file input still posts as an invalid "images[]" entry and fails validation.
        form.addEventListener('submit', () => {
            form.querySelectorAll('[data-file-input]').forEach(input => {
                if (!input.files || input.files.length === 0) input.disabled = true;
            });
        });
    })();
</script>
</x-app-layout>
