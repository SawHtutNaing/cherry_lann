<div class="container mx-auto mt-8">
    @if ($errors->any())
        {!! implode('', $errors->all('<div>:message</div>')) !!}
    @endif
    <h1 class="mb-6 text-2xl font-semibold">User Management</h1>
    <a href="{{ route('users.create') }}" class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">Create
        New User</a>
    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-100 border-b">
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Name</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Email</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Role</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Status</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $user->role }}</td>
                        <td class="px-6 py-4 text-sm {{ $user->status ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->status ? 'Enabled' : 'Disabled' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col sm:flex-row gap-2">
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="px-4 py-2 text-white bg-yellow-500 rounded shadow hover:bg-yellow-400 text-center">
                                    Edit
                                </a>
                                <button wire:click="toggleStatus({{ $user->id }})"
                                    class="px-4 py-2 text-white bg-red-500 rounded shadow hover:bg-red-400 text-center">
                                    {{ $user->status ? 'Disable' : 'Enable' }}
                                </button>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
