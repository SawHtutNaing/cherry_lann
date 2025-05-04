<div class="max-w-md p-6 mx-auto mt-8 bg-white rounded-md shadow-md">
    <h1 class="mb-6 text-2xl font-semibold">{{ $dataInputId ? 'Edit Data Input' : 'Create Data Input' }}</h1>
    <form wire:submit.prevent="save">
        <!-- Customer Name -->
        <div class="mb-4">
            <label class="block text-gray-700">Customer Name:</label>
            <input type="text" wire:model="customer_name"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('customer_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Page Name -->
        <div class="mb-4">
            <label class="block text-gray-700">Page Name:</label>
            <input type="text" wire:model="page_name"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('page_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <label class="block text-gray-700">Phone:</label>
            <input type="text" wire:model="phone"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('phone')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Boost Type -->
        <div class="mb-4">
            <label class="block text-gray-700">Boost Type:</label>
            <select wire:model="boost_type_id" name="boost_type_id"
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
            <input type="date" wire:model="start_date"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('start_date')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Amount -->
        <div class="mb-4">
            <label class="block text-gray-700">$ Amount:</label>
            <input type="number" step="0.01" wire:model.live="amount"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('amount')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- MM Kyat -->
        <div class="mb-4">
            <label class="block text-gray-700">MM Kyat:</label>
            <input type="number" step="0.01" wire:model.live="mm_kyat"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('mm_kyat')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Total Amount -->
        <div class="mb-4">
            <label class="block text-gray-700">Total Amount:</label>
            <input type="number" step="0.01" wire:model="total_amount" readonly
                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none">
            @error('total_amount')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label class="block text-gray-700">Status:</label>
            <select wire:model="status"
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
</div>
