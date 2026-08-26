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

    <form method="POST" action="{{ route('visas.update', $visa->id) }}" enctype="multipart/form-data">
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
            <label class="block text-gray-700">Add More Images:</label>
            <input type="file" name="images[]" multiple accept="image/*"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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
</script>
</x-app-layout>
