<div class="container mx-auto mt-8">
    @if (session()->has('message'))
        <div class="px-4 py-2 mb-4 text-white bg-green-500 rounded shadow">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="px-4 py-2 mb-4 text-white bg-red-500 rounded shadow">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="px-4 py-2 mb-4 text-white bg-red-500 rounded shadow">
            {!! implode('', $errors->all('<div>:message</div>')) !!}
        </div>
    @endif
    <h1 class="mb-6 text-2xl font-semibold">Service Type Management</h1>
    <button wire:click="openModal" class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
        Create New Service Type
    </button>
    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-100 border-b">
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Name</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Type</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Boost Types</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($serviceTypes as $serviceType)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $serviceType->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 uppercase">{{ $serviceType->type }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            @forelse ($serviceType->boostTypes as $boostType)
                                <span class="inline-block px-2 py-1 mb-1 mr-1 text-xs text-white bg-indigo-500 rounded">
                                    {{ $boostType->name }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-400">None</span>
                            @endforelse
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button wire:click="openModal({{ $serviceType->id }})"
                                    class="px-4 py-2 text-white bg-yellow-500 rounded shadow hover:bg-yellow-400 text-center">
                                    Edit
                                </button>
                                @if ($serviceType->type === 'dollar')
                                    <button wire:click="openExchangeModal({{ $serviceType->id }})"
                                        class="px-4 py-2 text-white bg-purple-600 rounded shadow hover:bg-purple-500 text-center">
                                        Exchange Rates
                                    </button>
                                @endif
                                <button wire:click="delete({{ $serviceType->id }})"
                                    wire:confirm="Are you sure you want to delete this service type?"
                                    class="px-4 py-2 text-white bg-red-600 rounded shadow hover:bg-red-500 text-center">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Create / Edit Service Type Modal --}}
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="w-full max-w-md p-6 mx-4 bg-white rounded shadow-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">
                        {{ $serviceTypeId ? 'Edit Service Type' : 'Create Service Type' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        &times;
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="mb-4">
                        <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" wire:model="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="mb-4">
                        <label for="type" class="block mb-1 text-sm font-medium text-gray-700">Type</label>
                        <select id="type" wire:model="type"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="mmk">MMK</option>
                            <option value="dollar">Dollar</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Boost Types</label>
                        <div class="grid grid-cols-2 gap-2 p-3 border border-gray-300 rounded max-h-40 overflow-y-auto">
                            @forelse ($boostTypes as $boostType)
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" value="{{ $boostType->id }}"
                                        wire:model="selectedBoostTypes"
                                        class="rounded border-gray-300 text-blue-500 focus:ring-blue-400">
                                    {{ $boostType->name }}
                                </label>
                            @empty
                                <span class="text-sm text-gray-400">No boost types available.</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded shadow hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                            {{ $serviceTypeId ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Exchange Rate Logs Modal --}}
    @if ($isExchangeModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="w-full max-w-2xl p-6 mx-4 bg-white rounded shadow-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">
                        Exchange Rates &mdash; {{ $exchangeServiceTypeName }}
                    </h2>
                    <button wire:click="closeExchangeModal" class="text-gray-400 hover:text-gray-600">
                        &times;
                    </button>
                </div>

                {{-- Add / Edit form --}}
                <form wire:submit.prevent="saveExchangeRate" class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-4">
                    <div>
                        <label for="exchangeAmount" class="block mb-1 text-sm font-medium text-gray-700">
                            Rate (1$ = ? MMK)
                        </label>
                        <input type="number" step="0.01" id="exchangeAmount" wire:model="exchangeAmount"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label for="exchangeStartDate" class="block mb-1 text-sm font-medium text-gray-700">
                            Start Date
                        </label>
                        <input type="date" id="exchangeStartDate" wire:model="exchangeStartDate"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label for="exchangeEndDate" class="block mb-1 text-sm font-medium text-gray-700">
                            End Date <span class="text-gray-400">(optional)</span>
                        </label>
                        <input type="date" id="exchangeEndDate" wire:model="exchangeEndDate"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="w-full px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                            {{ $exchangeLogId ? 'Update' : 'Add' }}
                        </button>
                        @if ($exchangeLogId)
                            <button type="button" wire:click="resetExchangeForm"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded shadow hover:bg-gray-300">
                                Cancel
                            </button>
                        @endif
                    </div>
                </form>

                {{-- Logs table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="w-full bg-gray-100 border-b">
                                <th class="px-4 py-2 text-sm font-medium text-left text-gray-600">Rate</th>
                                <th class="px-4 py-2 text-sm font-medium text-left text-gray-600">Start Date</th>
                                <th class="px-4 py-2 text-sm font-medium text-left text-gray-600">End Date</th>
                                <th class="px-4 py-2 text-sm font-medium text-left text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($exchangeRateLogs as $log)
                                <tr class="border-b">
                                    <td class="px-4 py-2 text-sm text-gray-800">{{ number_format($log->amount, 2) }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">{{ $log->start_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">
                                        {{ $log->end_date ? $log->end_date->format('Y-m-d') : '—' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex gap-2">
                                            <button wire:click="editExchangeRate({{ $log->id }})"
                                                class="px-3 py-1 text-sm text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">
                                                Edit
                                            </button>
                                            <button wire:click="deleteExchangeRate({{ $log->id }})"
                                                wire:confirm="Are you sure you want to delete this exchange rate?"
                                                class="px-3 py-1 text-sm text-white bg-red-600 rounded shadow hover:bg-red-500">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-sm text-center text-gray-500">
                                        No exchange rates recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
