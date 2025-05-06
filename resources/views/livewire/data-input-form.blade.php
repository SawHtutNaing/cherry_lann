<div class="max-w-md p-6 mx-auto mt-8 bg-white rounded-md shadow-md">
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

        <!-- Boost Type -->
        <div class="mb-4">
            <label class="block text-gray-700">Service Type:</label>
            <select wire:model.debounce.300ms="boost_type_id" name="boost_type_id"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select</option>
                @foreach ($boostTypes as $boostType)
                    <option value="{{ $boostType->id }}">{{ $boostType->name }}</option>
                @endforeach
            </select>
            @error('boost_type_id')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Start Date -->
        <div class="mb-4">
            <label class="block text-gray-700">Start Date:</label>
            <input type="date" wire:model.debounce.300ms="start_date"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('start_date')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Amount -->
        <div class="mb-4">
            <label class="block text-gray-700">Quantity</label>
            <input type="number" step="0.01" wire:model.debounce.300ms="amount" id="amount"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('amount')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- MM Kyat -->
        <div class="mb-4">
            <label class="block text-gray-700">Amount</label>
            <input type="number" step="0.01" wire:model.debounce.300ms="mm_kyat" id="mm_kyat"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('mm_kyat')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Total Amount -->
        <div class="mb-4">
            <label class="block text-gray-700">Total Amount:</label>
            <div class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100">
                <span id="total-amount">{{ number_format($total_amount, 2) }}</span>
            </div>
            @error('total_amount')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label class="block text-gray-700">Status:</label>
            <select wire:model.debounce.300ms="status"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select</option>
                <option value="1">Charge</option>
                <option value="2">Refund</option>
                <option value="3">Pending</option>
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


        <script>
            document.addEventListener('DOMContentLoaded', () => {

                try {
                    console.log('JavaScript loaded'); // Debug log
                    const amountInput = document.getElementById('amount');
                    const mmKyatInput = document.getElementById('mm_kyat');
                    const totalAmountSpan = document.getElementById('total-amount');

                    if (!amountInput || !mmKyatInput || !totalAmountSpan) {
                        console.error('Input elements not found');
                        return;
                    }

                    function updateTotalAmount() {
                        const amount = parseFloat(amountInput.value) || 0;
                        const mmKyat = parseFloat(mmKyatInput.value) || 0;
                        const total = amount * mmKyat;
                        totalAmountSpan.textContent = total.toFixed(2);
                        console.log('Client-side total:', total); // Debug log
                    }

                    amountInput.addEventListener('input', updateTotalAmount);
                    mmKyatInput.addEventListener('input', updateTotalAmount);

                    // Initial update
                    updateTotalAmount();
                } catch (error) {
                    console.error('JavaScript error:', error);
                }
            });
        </script>

</div>
