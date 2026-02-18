<div class="max-w-md p-6 mx-auto mt-8 bg-white rounded-md shadow-md">
    <h1 class="mb-6 text-2xl font-semibold">{{ $dataInputId ? 'Edit Data Input' : 'Create Data Input' }}</h1>
    <form wire:submit.prevent="save">
        <!-- Customer Name -->
        <div class="mb-4">
            <label class="block text-gray-700">Customer Name:</label>
            <input type="text" wire:model.debounce.300ms="customer_name"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('customer_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Page Name -->
        <div class="mb-4">
            <label class="block text-gray-700">Page Name:</label>
            <input type="text" wire:model.debounce.300ms="page_name"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('page_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <label class="block text-gray-700">Phone:</label>
            <input type="text" wire:model.debounce.300ms="phone"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('phone')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Boost Type -->
        <div class="mb-4">
            <label class="block text-gray-700">Service Type:</label>
            <select wire:model.debounce.300ms="boost_type_id" name="boost_type_id"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select</option>
                @foreach ($boostTypes as $boostType)
                    <option value="{{ $boostType->id }}">{{ $boostType->name }}</option>
                @endforeach
            </select>
            @error('boost_type_id')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Start Date -->
        <div class="mb-4">
            <label class="block text-gray-700">Start Date:</label>
            <input type="date" wire:model.debounce.300ms="start_date"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('start_date')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Amount -->
        <div class="mb-4">
            <label class="block text-gray-700">Quantity</label>
            <input type="number" step="0.01" wire:model.debounce.300ms="amount" id="amount"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('amount')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- MM Kyat -->
        <div class="mb-4">
            <label class="block text-gray-700">Amount</label>
            <input type="number" step="0.01" wire:model.debounce.300ms="mm_kyat" id="mm_kyat"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('mm_kyat')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Discount -->
        <div class="mb-4">
            <label class="block text-gray-700">Discount</label>
            <input type="number" step="0.01" wire:model.debounce.300ms="discount" id="discount"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('discount')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Total Amount -->
        <div class="mb-4">
            <label class="block text-gray-700">Total Amount:</label>
            <div class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100">
                <span id="total-amount">{{ number_format($total_amount, 2) }}</span>
            </div>
            @error('total_amount')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remark Toggle -->
        <div class="mb-4">
            <label class="block text-gray-700">Remark</label>
            <div class="w-full px-4 py-2 border-gray-300">
                <input type="checkbox"
                    @checked($is_remark)
                    wire:model.live="is_remark">
            </div>
            @error('is_remark')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        @if ($is_remark)
            <div class="mb-4" wire:transition>
                <label class="block text-gray-700">Remark Comment</label>
                <div class="w-full px-4 py-2 border-gray-300">
                    <input type="text" wire:model="remark"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @error('remark')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
        @endif

        <!-- Status -->
        <div class="mb-4">
            <label class="block text-gray-700">Status:</label>
            <select wire:model.live="status"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select</option>
                <option value="1">Charge</option>
                <option value="2">Refund</option>
                <option value="3">Pending</option>
            </select>
            @error('status')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        @if ((int) $status === 1)
            <!-- Client Side Image -->
            <div class="mb-4">
                <label class="block text-gray-700">Client Side Image:</label>
                <p class="mb-1 text-xs text-gray-400">Max 2MB · JPEG, PNG, WebP · Image will be compressed automatically.</p>
                <input
                    type="file"
                    id="client_side_image_input"
                    accept="image/jpeg,image/png,image/webp"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                @error('client_side_image')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror

                @if ($client_side_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                    <div class="mt-2">
                        <img src="{{ $client_side_image->temporaryUrl() }}" class="max-w-full h-auto object-cover rounded-md shadow-md">
                    </div>
                @elseif (is_string($client_side_image))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $client_side_image) }}" class="max-w-full h-auto object-cover rounded-md shadow-md">
                    </div>
                @endif
            </div>

            <!-- Service Side Image -->
            <div class="mb-4">
                <label class="block text-gray-700">Service Side Image:</label>
                <p class="mb-1 text-xs text-gray-400">Max 2MB · JPEG, PNG, WebP · Image will be compressed automatically.</p>
                <input
                    type="file"
                    id="service_side_image_input"
                    accept="image/jpeg,image/png,image/webp"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                @error('service_side_image')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror

                @if ($service_side_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                    <div class="mt-2">
                        <img src="{{ $service_side_image->temporaryUrl() }}" class="max-w-full h-auto object-cover rounded-md shadow-md">
                    </div>
                @elseif (is_string($service_side_image))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $service_side_image) }}" class="max-w-full h-auto object-cover rounded-md shadow-md">
                    </div>
                @endif
            </div>
        @endif

        <!-- Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 text-white bg-gray-500 rounded-md shadow hover:bg-gray-400">Cancel</a>
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md shadow hover:bg-blue-400">
                {{ $dataInputId ? 'Update' : 'Create' }}
            </button>
        </div>
    </form>
</div>

<script>
/**
 * Compresses an image File to JPEG with a max width and quality setting.
 * Returns a Promise that resolves to a new compressed File.
 */
function compressImage(file, maxWidth = 1024, quality = 0.75) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.onerror = () => reject(new Error('Failed to read file.'));

        reader.onload = (e) => {
            const img = new Image();

            img.onerror = () => reject(new Error('Failed to load image.'));

            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width  = img.width;
                let height = img.height;

                // Downscale only if wider than maxWidth
                if (width > maxWidth) {
                    height = Math.round(height * maxWidth / width);
                    width  = maxWidth;
                }

                canvas.width  = width;
                canvas.height = height;
                canvas.getContext('2d').drawImage(img, 0, 0, width, height);

                canvas.toBlob(
                    (blob) => {
                        if (!blob) return reject(new Error('Canvas compression failed.'));
                        resolve(new File([blob], file.name.replace(/\.[^.]+$/, '.jpg'), { type: 'image/jpeg' }));
                    },
                    'image/jpeg',
                    quality
                );
            };

            img.src = e.target.result;
        };

        reader.readAsDataURL(file);
    });
}

/**
 * Wires a plain <input type="file"> to a Livewire property via @this.upload().
 * Compresses the image client-side first, then uploads the smaller file.
 * Validation errors from the server are surfaced via Livewire's error bag.
 */
function wireImageInput(inputId, livewireProperty) {
    document.getElementById(inputId).addEventListener('change', async function (e) {
        const file = e.target.files[0];
        if (!file) return;

        // Basic client-side type guard before even hitting the server
        if (!file.type.match(/^image\/(jpeg|png|webp)$/)) {
            @this.addError(livewireProperty, 'The file must be a JPEG, PNG, or WebP image.');
            this.value = '';
            return;
        }

        // 2MB guard (matches server rule: max:2048)
        if (file.size > 2 * 1024 * 1024) {
            @this.addError(livewireProperty, 'The image must not be larger than 2MB.');
            this.value = '';
            return;
        }

        try {
            const compressed = await compressImage(file);

            @this.upload(
                livewireProperty,
                compressed,
                // ✅ Upload finished — Livewire will re-render and show the preview
                () => {},
                // ❌ Server-side error (e.g. failed validation) — Livewire populates $errors automatically
                (error) => {
                    console.error('Upload error for ' + livewireProperty + ':', error);
                },
                // ⏳ Progress (optional — wire up a progress bar here if needed)
                (progress) => {}
            );
        } catch (err) {
            @this.addError(livewireProperty, 'Could not process the image. Please try another file.');
            console.error(err);
        }
    });
}

// Boot after Livewire is ready so @this is available
document.addEventListener('livewire:init', () => {
    wireImageInput('client_side_image_input',  'client_side_image');
    wireImageInput('service_side_image_input', 'service_side_image');
});
</script>
