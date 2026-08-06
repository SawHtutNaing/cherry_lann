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

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Categories</h1>
        <button wire:click="createCategory" class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
            Add Category
        </button>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search categories..."
            class="w-full max-w-md px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-400 focus:border-transparent">
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-100 border-b">
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Name</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Slug</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Status</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Sort Order</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-sm {{ $category->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $category->sort_order }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button wire:click="editCategory({{ $category->id }})"
                                    class="px-4 py-2 text-white bg-yellow-500 rounded shadow hover:bg-yellow-400 text-center">
                                    Edit
                                </button>
                                <button wire:click="deleteCategory({{ $category->id }})"
                                    wire:confirm="Are you sure you want to delete this category?"
                                    class="px-4 py-2 text-white bg-red-600 rounded shadow hover:bg-red-500 text-center">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:ignore.self>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                <div class="relative bg-white rounded shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ $editingCategory ? 'Edit Category' : 'Create Category' }}
                    </h3>

                    <form wire:submit.prevent="saveCategory">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" wire:model="name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                                    required>
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                                <input type="text" wire:model="slug"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                                    required>
                                @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea wire:model="description" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-400 focus:border-transparent"></textarea>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" wire:model="is_active" id="is_active"
                                    class="h-4 w-4 text-blue-500 border-gray-300 rounded focus:ring-blue-400">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                <input type="number" wire:model="sort_order" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded shadow hover:bg-gray-300">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
                                {{ $editingCategory ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
