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

    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold">Expense Category Management</h1>
        <button wire:click="openModal" class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
            Create New Expense Category
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col gap-4 p-4 mb-6 bg-white border border-gray-200 rounded shadow sm:flex-row sm:items-end sm:flex-wrap">
        <div>
            <label for="filterFromDate" class="block mb-1 text-sm font-medium text-gray-700">From</label>
            <input type="date" id="filterFromDate" wire:model="filterFromDate"
                class="px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label for="filterToDate" class="block mb-1 text-sm font-medium text-gray-700">To</label>
            <input type="date" id="filterToDate" wire:model="filterToDate"
                class="px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <button wire:click="resetFilters"
            class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded shadow hover:bg-gray-300">
            Reset Filters
        </button>

        <div class="sm:ml-auto">
            <p class="text-sm text-gray-500">Grand Total (filtered)</p>
            <p class="text-xl font-bold text-gray-800">{{ number_format($grandTotal) }}</p>
        </div>
    </div>

  <div class="mt-6 overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="w-full bg-gray-100 border-b">
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">No.</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Name</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Total</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($expenseCategories as $expenseCategory)
                <tr class="border-b">
                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $expenseCategory->name }}</td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">
                        {{ number_format($expenseCategory->expenses_sum_amount ?? 0) }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col justify-center gap-2 sm:flex-row">
                            <button wire:click="openModal({{ $expenseCategory->id }})"
                                class="px-4 py-2 text-white bg-yellow-500 rounded shadow hover:bg-yellow-400 text-center">
                                Edit
                            </button>
                            <button wire:click="delete({{ $expenseCategory->id }})"
                                wire:confirm="Are you sure you want to delete this expense category?"
                                class="px-4 py-2 text-white bg-red-600 rounded shadow hover:bg-red-500 text-center">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="border-b">
                    <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">
                        No expense categories found.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($expenseCategories->count())
            <tfoot>
                <tr class="bg-gray-50">
                    <td></td>
                    <td class="px-6 py-3 text-sm font-bold text-center text-gray-600">Grand Total</td>
                    <td class="px-6 py-3 text-sm font-bold text-center text-gray-800">
                        {{ number_format($grandTotal) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="w-full max-w-md p-6 mx-4 bg-white rounded shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">
                        {{ $expenseCategoryId ? 'Edit Expense Category' : 'Create Expense Category' }}
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
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded shadow hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                            {{ $expenseCategoryId ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
