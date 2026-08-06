{{-- resources/views/livewire/site-setting-management.blade.php --}}
<div class="max-w-3xl mx-auto mt-8">
    @if (session('success'))
        <div class="px-4 py-2 mb-4 text-white bg-green-500 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="mb-6 text-2xl font-semibold">Site Settings</h1>

    <div class="space-y-8">

        {{-- Logo --}}
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Logo</label>
            <div x-data="settingsImageUploader(@js($logo_path), 'setLogoPath')"
                class="relative rounded border-2 border-dashed p-4 text-center"
                :class="dragging ? 'border-blue-400 bg-blue-50' : 'border-gray-300'"
                @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                @drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])">

                <template x-if="previewUrl">
                    <div class="relative inline-block">
                        <img :src="previewUrl" class="mx-auto h-20 rounded object-contain">
                        <button type="button" @click="removeImage('removeLogo')"
                            class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-xs text-white shadow">✕</button>
                    </div>
                </template>
                <template x-if="!previewUrl">
                    <div class="py-6">
                        <p class="text-sm text-gray-500">Drag & drop, or</p>
                        <button type="button" @click="$refs.logoInput.click()" class="text-sm font-medium text-blue-500 hover:underline">browse</button>
                    </div>
                </template>

                <input type="file" x-ref="logoInput" @change="handleFile($event.target.files[0])" accept="image/*" class="hidden">
                <div x-show="uploading" class="mt-3 h-1.5 w-full rounded-full bg-gray-200">
                    <div class="h-1.5 rounded-full bg-blue-500" :style="`width: ${progress}%`"></div>
                </div>
                <p x-show="error" x-text="error" class="mt-2 text-xs text-red-500"></p>
            </div>
        </div>

        {{-- Hero --}}
        <div class="pt-6 border-t">
            <h2 class="mb-4 text-lg font-semibold text-gray-700">Hero</h2>

            <div class="space-y-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Title</label>
                    <input type="text" wire:model="hero_title" class="w-full rounded border-gray-300 text-sm">
                    @error('hero_title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Subtitle</label>
                    <textarea wire:model="hero_subtitle" rows="2" class="w-full rounded border-gray-300 text-sm"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Button Text</label>
                        <input type="text" wire:model="hero_button_text" class="w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Button Link</label>
                        <input type="text" wire:model="hero_button_link" class="w-full rounded border-gray-300 text-sm">
                        @error('hero_button_link') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Hero Image</label>
                    <div x-data="settingsImageUploader(@js($hero_image_path), 'setHeroImagePath')"
                        class="relative rounded border-2 border-dashed p-4 text-center"
                        :class="dragging ? 'border-blue-400 bg-blue-50' : 'border-gray-300'"
                        @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                        @drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])">

                        <template x-if="previewUrl">
                            <div class="relative inline-block">
                                <img :src="previewUrl" class="mx-auto h-40 rounded object-cover">
                                <button type="button" @click="removeImage('removeHeroImage')"
                                    class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-xs text-white shadow">✕</button>
                            </div>
                        </template>
                        <template x-if="!previewUrl">
                            <div class="py-6">
                                <p class="text-sm text-gray-500">Drag & drop, or</p>
                                <button type="button" @click="$refs.heroInput.click()" class="text-sm font-medium text-blue-500 hover:underline">browse</button>
                            </div>
                        </template>

                        <input type="file" x-ref="heroInput" @change="handleFile($event.target.files[0])" accept="image/*" class="hidden">
                        <div x-show="uploading" class="mt-3 h-1.5 w-full rounded-full bg-gray-200">
                            <div class="h-1.5 rounded-full bg-blue-500" :style="`width: ${progress}%`"></div>
                        </div>
                        <p x-show="error" x-text="error" class="mt-2 text-xs text-red-500"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- About --}}
        <div class="pt-6 border-t">
            <h2 class="mb-4 text-lg font-semibold text-gray-700">About</h2>
            <div class="space-y-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Title</label>
                    <input type="text" wire:model="about_title" class="w-full rounded border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Subtitle</label>
                    <textarea wire:model="about_subtitle" rows="2" class="w-full rounded border-gray-300 text-sm"></textarea>
                </div>
            </div>
        </div>

        {{-- Social / Contact --}}
        <div class="pt-6 border-t">
            <h2 class="mb-4 text-lg font-semibold text-gray-700">Social Links</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Facebook URL</label>
                    <input type="text" wire:model="facebook_url" class="w-full rounded border-gray-300 text-sm">
                    @error('facebook_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Viber URL</label>
                    <input type="text" wire:model="viber_url" class="w-full rounded border-gray-300 text-sm">
                    @error('viber_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="pt-6 border-t">
            <h2 class="mb-4 text-lg font-semibold text-gray-700">Footer</h2>
            <label class="block mb-1 text-sm font-medium text-gray-700">Copyright Text</label>
            <input type="text" wire:model="footer_text" placeholder="© 2025 Cherry Lann Digital Marketing. All rights reserved."
                class="w-full rounded border-gray-300 text-sm">
        </div>

        <div class="flex justify-end pt-6 border-t">
            <button wire:click="save" wire:loading.attr="disabled"
                class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400 disabled:opacity-50">
                <span wire:loading.remove wire:target="save">Save Settings</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </div>
</div>

<script>
function settingsImageUploader(existingPath, setMethod) {
    return {
        dragging: false,
        uploading: false,
        progress: 0,
        error: '',
        previewUrl: existingPath ? '{{ Storage::disk('public')->url('') }}' + existingPath : null,
        wireId: null,

        init() {
            // Grab the enclosing Livewire component's id directly from the DOM.
            // This works regardless of which Alpine instance is running,
            // since it doesn't rely on the $wire magic property at all.
            const root = this.$el.closest('[wire\\:id]');
            this.wireId = root ? root.getAttribute('wire:id') : null;
        },

        callWire(method, ...args) {
            if (!this.wireId || typeof window.Livewire === 'undefined') {
                console.error('Livewire component not found for', method);
                return;
            }
            const component = window.Livewire.find(this.wireId);
            if (component) {
                component.call(method, ...args);
            }
        },

        handleFile(file) {
            this.error = '';
            if (!file) return;
            if (!file.type.startsWith('image/')) { this.error = 'Please select a valid image file.'; return; }
            if (file.size > 5 * 1024 * 1024) { this.error = 'Image must be smaller than 5MB.'; return; }

            this.previewUrl = URL.createObjectURL(file);

            const formData = new FormData();
            formData.append('image', file);

            this.uploading = true;
            this.progress = 0;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('cms-images.upload') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) this.progress = Math.round((e.loaded / e.total) * 100);
            });

            xhr.onload = () => {
                this.uploading = false;
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    this.previewUrl = data.url;
                    this.callWire(setMethod, data.path);
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

        removeImage(removeMethod) {
            this.previewUrl = null;
            this.callWire(removeMethod);
        },
    };
}
</script>
