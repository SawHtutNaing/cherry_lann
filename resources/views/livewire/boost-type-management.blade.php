
<div>
    <div class="container mx-auto mt-8">
        <h1 class="mb-6 text-2xl font-semibold">Service Types</h1>

        <!-- Success Message -->
        @if (session('success'))
            <div class="p-4 mb-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="p-4 mb-4 text-red-800 bg-red-100 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Create Button -->
        <div class="mb-6">
            <button wire:click="openModal"
                class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                Create New Service Type
            </button>
        </div>

        <!-- Boost Types Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="w-full bg-gray-100 border-b">
                        <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">No</th>
                        <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Name</th>
                        <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Created At</th>
                        <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Updated At</th>
                        <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($boostTypes as $boostType)
                        <tr class="border-b" wire:key='{{ $boostType->id }}'>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $boostType->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($boostType->created_at)->format('d/m/y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($boostType->updated_at)->format('d/m/y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="openModal({{ $boostType->id }})"
                                    class="px-4 py-2 text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">Edit</button>
                                <button wire:confirm="Are you sure you want to delete this boost type?"
                                    wire:click="delete({{ $boostType->id }})"
                                    class="px-4 py-2 text-white bg-red-500 rounded shadow hover:bg-red-400">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal for Create/Edit -->
        @if ($isOpen)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="w-full max-w-md p-6 bg-white rounded-md shadow-md">
                    <h2 class="mb-4 text-xl font-semibold">{{ $boostTypeId ? 'Edit Boost Type' : 'Create Boost Type' }}</h2>
                    <form wire:submit.prevent="save">
                        <!-- General Error -->
                        @error('general')
                            <span class="block mb-4 text-red-500">{{ $message }}</span>
                        @enderror

                        <!-- Name -->
                        <div class="mb-4">
                            <label class="block text-gray-700">Name:</label>
                            <input type="text" wire:model="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-white bg-gray-500 rounded shadow hover:bg-gray-400">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                                {{ $boostTypeId ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
