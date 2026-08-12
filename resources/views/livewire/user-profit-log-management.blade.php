<div class="container mx-auto mt-8 px-4">
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

    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}"
                class="flex items-center gap-1 px-3 py-2 text-sm text-gray-700 bg-gray-200 rounded shadow hover:bg-gray-300">
                &larr; Back
            </a>
            <h1 class="text-xl font-semibold sm:text-2xl">
                Profit Logs &mdash; {{ $user->name }}
            </h1>
        </div>
        <button wire:click="openModal"
            class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
            Add Profit Log
        </button>
    </div>

    {{-- Desktop table --}}
    <div class="hidden overflow-x-auto md:block">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-100 border-b">
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Boost Type</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Type</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">From</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">To</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($profitLogs as $log)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $log->boostType->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 capitalize">{{ $log->type }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $log->type === 'percentage' ? $log->amount . '%' : number_format($log->amount) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $log->from_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $log->to_date ? $log->to_date->format('Y-m-d') : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button wire:click="openModal({{ $log->id }})"
                                    class="px-3 py-1 text-sm text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $log->id }})"
                                    wire:confirm="Are you sure you want to delete this profit log?"
                                    class="px-3 py-1 text-sm text-white bg-red-600 rounded shadow hover:bg-red-500">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">
                            No profit logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="space-y-4 md:hidden">
        @forelse ($profitLogs as $log)
            <div class="p-4 bg-white border border-gray-200 rounded shadow">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $log->boostType->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ $log->type }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-800">
                        {{ $log->type === 'percentage' ? $log->amount . '%' : number_format($log->amount) }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-3 text-xs text-gray-600">
                    <div>
                        <span class="block text-gray-400">From</span>
                        {{ $log->from_date->format('Y-m-d') }}
                    </div>
                    <div>
                        <span class="block text-gray-400">To</span>
                        {{ $log->to_date ? $log->to_date->format('Y-m-d') : '—' }}
                    </div>
                </div>
                <div class="flex gap-2">
                    <button wire:click="openModal({{ $log->id }})"
                        class="flex-1 px-3 py-2 text-sm text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">
                        Edit
                    </button>
                    <button wire:click="delete({{ $log->id }})"
                        wire:confirm="Are you sure you want to delete this profit log?"
                        class="flex-1 px-3 py-2 text-sm text-white bg-red-600 rounded shadow hover:bg-red-500">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <p class="text-sm text-center text-gray-500">No profit logs found.</p>
        @endforelse
    </div>

    {{-- Create / Edit Modal --}}
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black bg-opacity-50">
            <div class="w-full max-w-md p-6 bg-white rounded shadow-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">
                        {{ $profitLogId ? 'Edit Profit Log' : 'Add Profit Log' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        &times;
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="mb-4">
                        <label for="boost_type_id" class="block mb-1 text-sm font-medium text-gray-700">
                            Boost Type
                        </label>
                        <select id="boost_type_id" wire:model="boost_type_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Select a boost type</option>
                            @foreach ($boostTypes as $boostType)
                                <option value="{{ $boostType->id }}">{{ $boostType->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block mb-1 text-sm font-medium text-gray-700">Type</label>
                        <select id="type" wire:model="type"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="flat">Flat</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="block mb-1 text-sm font-medium text-gray-700">Amount</label>
                        <input type="number" step="0.01" id="amount" wire:model="amount"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
                        <div>
                            <label for="from_date" class="block mb-1 text-sm font-medium text-gray-700">
                                From Date
                            </label>
                            <input type="date" id="from_date" wire:model="from_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div>
                            <label for="to_date" class="block mb-1 text-sm font-medium text-gray-700">
                                To Date <span class="text-gray-400">(optional)</span>
                            </label>
                            <input type="date" id="to_date" wire:model="to_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded shadow hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                            {{ $profitLogId ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
