<div class="max-w-2xl p-6 mx-auto mt-8 bg-white rounded-md shadow-md">
    <h1 class="mb-6 text-2xl font-semibold">{{ $dataInputId ? 'Edit Data Input' : 'Create Data Input' }}</h1>
    <form wire:submit.prevent="save">

        <!-- Customer Name -->
        <div class="mb-4">
            <label class="block text-gray-700">Customer Name:</label>
            <input type="text" wire:model.debounce.300ms="customer_name"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('customer_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Page Name -->
        <div class="mb-4">
            <label class="block text-gray-700">Page Name:</label>
            <input type="text" wire:model.debounce.300ms="page_name"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('page_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <label class="block text-gray-700">Phone:</label>
            <input type="text" wire:model.debounce.300ms="phone"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('phone')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- ═══════════════ Line Items ═══════════════ -->
        <h2 class="mt-6 mb-2 text-lg font-semibold text-gray-800">Service Items</h2>

        @foreach ($items as $index => $item)
            <div class="mb-4 p-4 border border-gray-200 rounded-md bg-gray-50" wire:key="item-{{ $index }}">
                <div class="flex items-center justify-between mb-3">
                    <span class="font-semibold text-gray-600">Item #{{ $index + 1 }}</span>
                    @if (count($items) > 1)
                        <button type="button" wire:click="removeItem({{ $index }})"
                            class="text-sm font-medium text-red-500 hover:text-red-700 hover:underline">
                            Remove
                        </button>
                    @endif
                </div>

                <!-- Boost Type -->
                <div class="mb-3">
                    <label class="block text-gray-700">Service Type:</label>
                    <select wire:model="items.{{ $index }}.boost_type_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select</option>
                        @foreach ($boostTypes as $boostType)
                            <option value="{{ $boostType->id }}">{{ $boostType->name }}</option>
                        @endforeach
                    </select>
                    @error("items.$index.boost_type_id")
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Start Date -->
                <div class="mb-3">
                    <label class="block text-gray-700">Start Date:</label>
                    <input type="date" wire:model="items.{{ $index }}.start_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error("items.$index.start_date")
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <!-- Amount (Qty) -->
                    <div>
                        <label class="block text-gray-700">Quantity</label>
                        <input type="number" step="0.01"
                            wire:model.live.debounce.300ms="items.{{ $index }}.amount"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error("items.$index.amount")
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- MM Kyat -->
                    <div>
                        <label class="block text-gray-700">Amount</label>
                        <input type="number" step="0.01"
                            wire:model.live.debounce.300ms="items.{{ $index }}.mm_kyat"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error("items.$index.mm_kyat")
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Discount -->
                    <div>
                        <label class="block text-gray-700">Discount</label>
                        <input type="number" step="0.01"
                            wire:model.live.debounce.300ms="items.{{ $index }}.discount"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error("items.$index.discount")
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <p class="mt-3 text-sm font-semibold text-right text-gray-700">
                    Line Total: {{ number_format($item['line_total'], 2) }}
                </p>
            </div>
        @endforeach

        <button type="button" wire:click="addItem"
            class="mb-6 px-4 py-2 text-sm font-medium text-blue-600 border border-blue-500 rounded-md hover:bg-blue-50">
            + Add Item
        </button>

        <!-- Total Amount -->
        <div class="mb-4">
            <label class="block text-gray-700">Total Amount:</label>
            <div class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100">
                <span>{{ number_format($total_amount, 2) }}</span>
            </div>
            @error('total_amount')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remark toggle -->
        <div class="mb-4">
            <label class="block text-gray-700">Remark </label>
            <div class="w-full px-4 py-2 border-gray-300">
                <input type="checkbox" @checked($is_remark) wire:model.live="is_remark">
            </div>
            @error('is_remark')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        @if ($is_remark)
            <div class="mb-4" wire:transition>
                <label class="block text-gray-700">Remark Comment</label>
                <div class="w-full px-4 py-2 border-gray-300">
                    <input type="text" wire:model="remark"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @error('remark')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
        @endif

        <!-- Status -->
        <div class="mb-4">
            <label class="block text-gray-700">Status:</label>
            <select wire:model.debounce.300ms="status"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select</option>
                <option value="1">Charge</option>
                <option value="2">Refund</option>
                <option value="3">Pending</option>
                <option value="4">Ongoing</option>
            </select>
            @error('status')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 text-white bg-gray-500 rounded-md shadow hover:bg-gray-400">Cancel</a>
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md shadow hover:bg-blue-400">
                {{ $dataInputId ? 'Update' : 'Create' }}
            </button>
        </div>
    </form>
</div>
