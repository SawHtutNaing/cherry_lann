<div class="max-w-md p-6 mx-auto mt-8 bg-white rounded-md shadow-md">
    {{-- @if ($errors->any())
        {!! implode('', $errors->all('<div>:message</div>')) !!}
    @endif --}}
    <h1 class="mb-6 text-2xl font-semibold">{{ $dataInputId ? 'Edit Data Input' : 'Create Data Input' }}</h1>
    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label class="block text-gray-700">Page Name:</label>
            <input type="text" wire:model="page_name"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('page_name')
                <span class="text-red-500 "> {{ $message }} </span>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Boost Type:</label>
            <select wire:model="boost_type_id" name='boost_type_id'
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>Select </option>
                @foreach (App\Models\BoostType::all() as $boostType)
                    <option value="{{ $boostType->id }}">{{ $boostType->name }}</option>
                @endforeach
            </select>
            @error('boost_type_id')
                <span class="text-red-500 "> {{ $message }} </span>
            @enderror

        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Start Date:</label>
            <input type="date" wire:model="start_date"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('start_date')
                <span class="text-red-500 "> {{ $message }} </span>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">$ Amount:</label>
            <input type="number" step="0.01" wire:model.live="amount"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('amount')
                <span class="text-red-500 "> {{ $message }} </span>
            @enderror

        </div>
        <div class="mb-4">
            <label class="block text-gray-700">MM Kyat:</label>
            <input type="number" step="0.01" wire:model.live="mm_kyat"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('mm_kyat')
                <span class="text-red-500 "> {{ $message }} </span>
            @enderror

        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Total Amount:</label>
            {{-- <input  wire:model.live="total_amount"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"> --}}
                {{ $total_amount }}
                {{-- @error('total_amount')
                <span class="text-red-500 "> {{ $message }} </span>
            @enderror --}}

        </div>



        <div class="mb-4">
            <label class="block text-gray-700">Status:</label>
            <select wire:model="status"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

                <option>Select</option>
                <option value="1">Charge</option>
                <option value="2">Refund</option>
            </select>
            @error('status')
                <span class="text-red-500 "> {{ $message }} </span>
            @enderror

        </div>
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md shadow hover:bg-blue-400">
            {{ $dataInputId ? 'Update' : 'Create' }}
        </button>
    </form>
</div>
