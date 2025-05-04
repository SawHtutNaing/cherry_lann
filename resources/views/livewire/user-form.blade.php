<div class="max-w-md p-6 mx-auto mt-8 bg-white rounded-md shadow-md">
    @if ($errors->any())
        {!! implode('', $errors->all('<div>:message</div>')) !!}
    @endif
    <h1 class="mb-6 text-2xl font-semibold">{{ $userId ? 'Edit User' : 'Create User' }}</h1>
    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label class="block text-gray-700">Name:</label>
            <input type="text" wire:model="name"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Email:</label>
            <input type="email" wire:model="email"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Password:</label>
            <input type="password" wire:model="password"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Role:</label>

            <select wire:model="role"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>Select Role </option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md shadow hover:bg-blue-400">
            {{ $userId ? 'Update' : 'Create' }}
        </button>
    </form>
</div>
