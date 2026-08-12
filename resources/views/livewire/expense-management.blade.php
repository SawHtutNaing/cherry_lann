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
    <h1 class="mb-6 text-2xl font-semibold">Expense Management</h1>
    <button wire:click="openModal" class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
        Create New Expense
    </button>
    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-100 border-b">
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Date</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Category</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Remark</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenses as $expense)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $expense->date?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $expense->expenseCategory->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $expense->remark ?: '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ number_format($expense->amount) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col sm:flex-row gap-2">
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
                        <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">
                            No expenses found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
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
