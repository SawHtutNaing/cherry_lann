<div>
    <div class="container mx-auto mt-8">
        <h1 class="mb-6 text-2xl font-semibold">Regions</h1>

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
                Create New Region
            </button>
        </div>

        @php
            $active = $regions->where('is_active', true)->values();
            $disabled = $regions->where('is_active', false)->values();
        @endphp

        <!-- Regions Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="w-full bg-gray-100 border-b">
                        <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">No</th>
                        <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Name</th>
                        <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Created At</th>
                        <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Updated At</th>
                        <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Status</th>
                        <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Enabled regions --}}
                    @foreach ($active as $index => $region)
                        <tr class="border-b" wire:key='{{ $region->id }}'>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $region->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($region->created_at)->format('d/m/y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($region->updated_at)->format('d/m/y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-1 text-xs text-white bg-green-500 rounded">
                                    Enabled
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <button wire:click="openModal({{ $region->id }})"
                                        class="w-full sm:w-20 px-3 py-2 text-sm text-center text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">Edit</button>
                                    <button wire:click="toggleStatus({{ $region->id }})"
                                        wire:confirm="Disable this region?"
                                        class="w-full sm:w-20 px-3 py-2 text-sm text-center text-white bg-gray-500 rounded shadow hover:bg-gray-400">Disable</button>
                                    <button wire:confirm="Are you sure you want to delete this region?"
                                        wire:click="delete({{ $region->id }})"
                                        class="w-full sm:w-20 px-3 py-2 text-sm text-center text-white bg-red-500 rounded shadow hover:bg-red-400">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    {{-- Divider --}}
                    @if ($disabled->count() > 0)
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-100 border-y">
                                Disabled Regions
                            </td>
                        </tr>
                    @endif

                    {{-- Disabled regions --}}
                    @foreach ($disabled as $index => $region)
                        <tr class="border-b bg-gray-50" wire:key='{{ $region->id }}'>
                            <td class="px-6 py-4 text-sm text-red-400">{{ $active->count() + $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-red-500">{{ $region->name }}</td>
                            <td class="px-6 py-4 text-sm text-red-500">
                                {{ \Carbon\Carbon::parse($region->created_at)->format('d/m/y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-red-500">
                                {{ \Carbon\Carbon::parse($region->updated_at)->format('d/m/y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-1 text-xs text-white bg-red-500 rounded">
                                    Disabled
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <button wire:click="openModal({{ $region->id }})"
                                        class="w-full sm:w-20 px-3 py-2 text-sm text-center text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">Edit</button>
                                    <button wire:click="toggleStatus({{ $region->id }})"
                                        class="w-full sm:w-20 px-3 py-2 text-sm text-center text-white bg-green-600 rounded shadow hover:bg-green-500">Enable</button>
                                    <button wire:confirm="Are you sure you want to delete this region?"
                                        wire:click="delete({{ $region->id }})"
                                        class="w-full sm:w-20 px-3 py-2 text-sm text-center text-white bg-red-500 rounded shadow hover:bg-red-400">Delete</button>
                                </div>
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
                    <h2 class="mb-4 text-xl font-semibold">{{ $regionId ? 'Edit Region' : 'Create Region' }}</h2>
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
                                {{ $regionId ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
