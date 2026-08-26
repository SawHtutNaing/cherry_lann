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
            <label class="block text-gray-700">Images:</label>
            <input type="file" name="images[]" multiple accept="image/*"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="mt-1 text-xs text-gray-400">You can select multiple images at once.</p>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('visas.index') }}"
                class="px-4 py-2 text-white bg-gray-500 rounded-md shadow hover:bg-gray-400">Cancel</a>
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md shadow hover:bg-blue-400">Create</button>
        </div>
    </form>
</div>
</x-app-layout>
