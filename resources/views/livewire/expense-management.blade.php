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
        <h1 class="text-2xl font-semibold">Expense Management</h1>
        <button wire:click="openModal" class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
            Create New Expense
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

        {{-- Category multi-select (checkbox dropdown) --}}
        <div x-data="{ open: false }" class="relative">
            <label class="block mb-1 text-sm font-medium text-gray-700">Category</label>
            <button type="button" @click="open = !open" @click.outside="open = false"
                class="flex items-center justify-between w-48 gap-2 px-3 py-2 text-sm text-left text-gray-700 bg-white border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <span>
                    {{ count($filterCategoryIds) ? count($filterCategoryIds) . ' selected' : 'All categories' }}
                </span>
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-cloak
                class="absolute z-20 w-56 p-3 mt-1 overflow-y-auto bg-white border border-gray-200 rounded shadow-lg max-h-56">
                @forelse ($expenseCategories as $category)
                    <label class="flex items-center gap-2 py-1 text-sm text-gray-700 cursor-pointer select-none">
                        <input type="checkbox" wire:model="filterCategoryIds" value="{{ $category->id }}"
                            class="text-blue-500 border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-blue-400">
                        {{ $category->name }}
                    </label>
                @empty
                    <p class="text-sm text-gray-400">No categories found.</p>
                @endforelse
            </div>
        </div>

        <button wire:click="resetFilters"
            class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded shadow hover:bg-gray-300">
            Reset Filters
        </button>

        <div class="sm:ml-auto">
            <p class="text-sm text-gray-500">Total (filtered)</p>
            <p class="text-xl font-bold text-gray-800">{{ number_format($totalAmount) }}</p>
        </div>
    </div>

<div class="mt-6 overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="w-full bg-gray-100 border-b">
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">No.</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Amount</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Category</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Date</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Remark</th>
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($expenses as $expense)
                <tr class="border-b">
                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">
                        {{ number_format($expense->amount) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">
                        {{ $expense->expenseCategory->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">
                        {{ $expense->date?->format('d M Y') ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">
                        {{ $expense->remark ?: '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col justify-center gap-2 sm:flex-row">
                            <button wire:click="openModal({{ $expense->id }})"
                                class="px-4 py-2 text-white bg-yellow-500 rounded shadow hover:bg-yellow-400 text-center">
                                Edit
                            </button>
                            <button wire:click="delete({{ $expense->id }})"
                                wire:confirm="Are you sure you want to delete this expense?"
                                class="px-4 py-2 text-white bg-red-600 rounded shadow hover:bg-red-500 text-center">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="border-b">
                    <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">
                        No expenses found.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($expenses->count())
            <tfoot>
                <tr class="bg-gray-50">
                    <td></td>
                    <td class="px-6 py-3 text-sm font-bold text-center text-gray-800">
                        {{ number_format($totalAmount) }}
                    </td>
                    <td colspan="4" class="px-6 py-3 text-sm font-bold text-center text-gray-600">
                        Total ({{ $expenses->count() }} expense{{ $expenses->count() === 1 ? '' : 's' }})
                    </td>
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
                        {{ $expenseId ? 'Edit Expense' : 'Create Expense' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        &times;
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="mb-4">
                        <label for="expense_category_id" class="block mb-1 text-sm font-medium text-gray-700">
                            Category
                        </label>
                        <select id="expense_category_id" wire:model="expense_category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Select a category</option>
                            @foreach ($expenseCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('expense_category_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="date" class="block mb-1 text-sm font-medium text-gray-700">Date</label>
                        <input type="date" id="date" wire:model="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('date') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="block mb-1 text-sm font-medium text-gray-700">Amount</label>
                        <input type="number" id="amount" wire:model="amount" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('amount') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="remark" class="block mb-1 text-sm font-medium text-gray-700">
                            Remark <span class="text-gray-400">(optional)</span>
                        </label>
                        <textarea id="remark" wire:model="remark" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                        @error('remark') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded shadow hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                            {{ $expenseId ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
