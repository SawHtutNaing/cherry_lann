<div class="container mx-auto mt-8">
    @if (session('success'))
        <div class="px-4 py-2 mb-4 text-white bg-green-500 rounded shadow">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="px-4 py-2 mb-4 text-white bg-red-500 rounded shadow">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="px-4 py-2 mb-4 text-white bg-red-500 rounded shadow">
            {!! implode('', $errors->all('<div>:message</div>')) !!}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Image Management</h1>
            <p class="text-sm text-gray-500">Manage images grouped by category</p>
        </div>

        <div class="flex items-center gap-3">
            <select wire:model.live="categoryFilter"
                class="rounded border-gray-300 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                <option value="">All Categories</option>
                @foreach ($allCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <button wire:click="openCreateModal"
                class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                Add Image
            </button>
        </div>
    </div>

    <div class="space-y-8">
        @forelse ($categoriesWithImages as $category)
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <h2 class="text-lg font-semibold text-gray-700">{{ $category->name }}</h2>
                    <span class="text-xs text-gray-400">({{ $category->images->count() }})</span>
                </div>

                @if ($category->images->isEmpty())
                    <p class="text-sm text-gray-400 italic">No images in this category.</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        @foreach ($category->images as $image)
                            <div class="group relative rounded border border-gray-200 bg-white overflow-hidden shadow-sm hover:shadow transition">
                                <div class="aspect-square bg-gray-100">
                                    <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}" class="w-full h-full object-cover">
                                </div>

                                <div class="p-3">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $image->title }}</p>
                                    <p class="text-xs text-gray-400">Order: {{ $image->sort_order }}</p>
                                </div>

                                <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition">
                                    <button wire:click="toggleActive({{ $image->id }})" class="rounded bg-white/90 p-1.5 shadow hover:bg-white" title="Toggle active">
                                        @if ($image->is_active)
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        @endif
                                    </button>

                                    <button wire:click="openEditModal({{ $image->id }})" class="rounded bg-white/90 p-1.5 shadow hover:bg-white" title="Edit">
                                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>

                                    <button wire:click="delete({{ $image->id }})" wire:confirm="Delete this image?" class="rounded bg-white/90 p-1.5 shadow hover:bg-white" title="Delete">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" /></svg>
                                    </button>
                                </div>

                                @unless ($image->is_active)
                                    <div class="absolute inset-0 bg-white/60 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-500 bg-white px-2 py-0.5 rounded-full shadow">Inactive</span>
                                    </div>
                                @endunless
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-center text-gray-400 py-12">No categories found.</p>
        @endforelse
    </div>

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-75 px-4" wire:keydown.escape="closeModal">
            <div class="w-full max-w-lg rounded bg-white shadow-xl" x-data="imageUploader(@js($image_path))" x-init="init()">
                <div class="flex items-center justify-between border-b px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $isEditing ? 'Edit Image' : 'Add Image' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="px-6 py-4 space-y-4 max-h-[70vh] overflow-y-auto">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>

                        <div
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])"
                            :class="dragging ? 'border-blue-400 bg-blue-50' : 'border-gray-300'"
                            class="relative rounded border-2 border-dashed p-4 text-center transition">

                            <template x-if="previewUrl">
                                <div class="relative inline-block">
                                    <img :src="previewUrl" class="mx-auto h-40 rounded object-cover">
                                    <button type="button" @click="removeImage()" class="absolute -top-2 -right-2 rounded-full bg-red-500 text-white w-6 h-6 flex items-center justify-center text-xs shadow">✕</button>
                                </div>
                            </template>

                            <template x-if="!previewUrl">
                                <div class="py-6">
                                    <p class="text-sm text-gray-500">Drag & drop an image, or</p>
                                    <button type="button" @click="$refs.fileInput.click()" class="mt-1 text-sm font-medium text-blue-500 hover:underline">browse a file</button>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF, WEBP — max 5MB</p>
                                </div>
                            </template>

                            <input type="file" x-ref="fileInput" @change="handleFile($event.target.files[0])" accept="image/*" class="hidden">

                            <div x-show="uploading" class="mt-3">
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-blue-500 h-1.5 rounded-full transition-all" :style="`width: ${progress}%`"></div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1" x-text="`Uploading... ${progress}%`"></p>
                            </div>

                            <p x-show="error" x-text="error" class="text-xs text-red-500 mt-2"></p>
                        </div>

                        @error('image_path') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select wire:model="category_id" class="w-full rounded border-gray-300 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                            <option value="">Select category</option>
                            @foreach ($allCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" wire:model="title" class="w-full rounded border-gray-300 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                        <input type="text" wire:model="alt_text" class="w-full rounded border-gray-300 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="2" class="w-full rounded border-gray-300 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                        <input type="text" wire:model="link_url" class="w-full rounded border-gray-300 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                        @error('link_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <input type="number" wire:model="sort_order" class="w-full rounded border-gray-300 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                        </div>

                        <label class="flex items-center gap-2 mt-5">
                            <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-blue-500 focus:ring-blue-400">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t px-6 py-4">
                    <button wire:click="closeModal" class="px-4 py-2 text-gray-700 bg-gray-200 rounded shadow hover:bg-gray-300">Cancel</button>
                    <button wire:click="save" wire:loading.attr="disabled"
                        class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400 disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Update' : 'Save' }}</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function imageUploader(existingPath) {
    return {
        dragging: false,
        uploading: false,
        progress: 0,
        error: '',
        previewUrl: existingPath ? '{{ Storage::disk('public')->url('') }}' + existingPath : null,

        init() {},

        handleFile(file) {
            this.error = '';
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                this.error = 'Please select a valid image file.';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                this.error = 'Image must be smaller than 5MB.';
                return;
            }

            this.previewUrl = URL.createObjectURL(file);

            const formData = new FormData();
            formData.append('image', file);
            @if ($isEditing)
                formData.append('id', {{ $editingId ?? 'null' }});
            @endif

            this.uploading = true;
            this.progress = 0;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('cms-images.upload') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    this.progress = Math.round((e.loaded / e.total) * 100);
                }
            });

            xhr.onload = () => {
                this.uploading = false;
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    this.previewUrl = data.url;
                    this.$wire.setImagePath(data.path);
                } else {
                    this.error = 'Upload failed. Please try again.';
                    this.previewUrl = null;
                }
            };

            xhr.onerror = () => {
                this.uploading = false;
                this.error = 'Upload failed. Please check your connection.';
            };

            xhr.send(formData);
        },

        removeImage() {
            this.previewUrl = null;
            this.$wire.removeImage();
        },
    };
}
</script>
