<x-app-layout>
<div class="max-w-xl p-6 mx-auto mt-8 bg-white rounded-md shadow-md">
    <h1 class="mb-6 text-2xl font-semibold">Create Visa</h1>

    @if ($errors->any())
        <div class="p-4 mb-4 text-red-800 bg-red-100 rounded">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('visas.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700">Amount:</label>
            <input type="number" name="amount" value="{{ old('amount') }}" min="0" step="1"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="mt-1 text-xs text-gray-400">Enter the whole number amount — it will be shown with money formatting on the list page.</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Date:</label>
            <input type="date" name="date" value="{{ old('date') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Remark:</label>
            <textarea name="remark" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('remark') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-gray-700">Images:</label>
            <div id="imageDropzone"
                class="flex flex-col items-center justify-center gap-1 p-6 text-center transition border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-blue-400 hover:bg-blue-50">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 8.25 12 3.75m0 0L7.5 8.25M12 3.75v13.5" />
                </svg>
                <p class="text-sm text-gray-600"><span class="font-medium text-blue-600">Click to upload</span> or drag and drop</p>
                <p class="text-xs text-gray-400">You can select multiple images at once (max 5MB each)</p>
                <input type="file" id="imagesInput" name="images[]" multiple accept="image/*" class="hidden">
            </div>
            <div id="imagePreviewGrid" class="grid grid-cols-4 gap-2 mt-3 sm:grid-cols-5"></div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('visas.index') }}"
                class="px-4 py-2 text-white bg-gray-500 rounded-md shadow hover:bg-gray-400">Cancel</a>
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md shadow hover:bg-blue-400">Create</button>
        </div>
    </form>
</div>

<script>
    (function () {
        const dropzone = document.getElementById('imageDropzone');
        const input = document.getElementById('imagesInput');
        const previewGrid = document.getElementById('imagePreviewGrid');
        let selectedFiles = [];

        dropzone.addEventListener('click', () => input.click());

        dropzone.addEventListener('dragover', e => {
            e.preventDefault();
            dropzone.classList.add('border-blue-400', 'bg-blue-50');
        });
        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-blue-400', 'bg-blue-50');
        });
        dropzone.addEventListener('drop', e => {
            e.preventDefault();
            dropzone.classList.remove('border-blue-400', 'bg-blue-50');
            addFiles(e.dataTransfer.files);
        });

        input.addEventListener('click', e => e.stopPropagation());
        input.addEventListener('change', () => addFiles(input.files));

        function addFiles(fileList) {
            for (const file of fileList) {
                if (file.type.startsWith('image/')) selectedFiles.push(file);
            }
            syncInput();
            renderPreviews();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            syncInput();
            renderPreviews();
        }

        // Rebuild the real <input> FileList so the removed/added files are what actually submits.
        function syncInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            input.files = dt.files;
        }

        function renderPreviews() {
            previewGrid.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const url = URL.createObjectURL(file);
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group';
                wrapper.innerHTML = `
                    <img src="${url}" class="object-cover w-full border border-gray-200 rounded h-20">
                    <button type="button"
                        class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full shadow -top-1.5 -right-1.5 hover:bg-red-600">
                        ✕
                    </button>
                `;
                wrapper.querySelector('button').addEventListener('click', () => removeFile(index));
                previewGrid.appendChild(wrapper);
            });
        }
    })();
</script>
</x-app-layout>
