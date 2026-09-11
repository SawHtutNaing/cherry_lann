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
                <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Images</th>
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
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-wrap justify-center gap-1" id="expense-images-{{ $expense->id }}">
                            @forelse ($expense->images as $image)
                                <img src="{{ $image->url }}"
                                    class="object-cover w-10 h-10 border border-gray-200 rounded cursor-pointer hover:opacity-80"
                                    onclick="openExpenseImageModal('{{ $image->url }}', 'expense-images-{{ $expense->id }}')">
                            @empty
                                <span class="text-xs text-gray-400">No images</span>
                            @endforelse
                        </div>
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
                    <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500">
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
                    <td colspan="5" class="px-6 py-3 text-sm font-bold text-center text-gray-600">
                        Total ({{ $expenses->count() }} expense{{ $expenses->count() === 1 ? '' : 's' }})
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>

    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="w-full max-w-md p-6 mx-4 overflow-y-auto bg-white rounded shadow-lg max-h-[90vh]">
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

                    @if (count($currentImages))
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Existing Images</label>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($currentImages as $image)
                                    <div class="relative">
                                        <img src="{{ $image['url'] }}" class="object-cover w-full h-16 border border-gray-200 rounded">
                                        <button type="button" wire:click="removeExistingImage({{ $image['id'] }})"
                                            wire:confirm="Delete this image?"
                                            class="absolute top-0.5 right-0.5 px-1.5 py-0.5 text-xs text-white bg-red-500 rounded shadow hover:bg-red-600">
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="newImages" class="block mb-1 text-sm font-medium text-gray-700">
                            {{ count($currentImages) ? 'Add More Images' : 'Images' }} <span class="text-gray-400">(optional)</span>
                        </label>
                        <input type="file" id="newImages" wire:model="newImages" multiple accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <p class="mt-1 text-xs text-gray-400">You can select multiple images at once.</p>
                        <div wire:loading wire:target="newImages" class="mt-1 text-xs text-gray-500">Uploading…</div>
                        @error('newImages.*') <span class="text-sm text-red-500">{{ $message }}</span> @enderror

                        @if (count($newImages))
                            <div class="grid grid-cols-4 gap-2 mt-2">
                                @foreach ($newImages as $index => $image)
                                    <div class="relative">
                                        <img src="{{ $image->temporaryUrl() }}" class="object-cover w-full h-16 border border-gray-200 rounded">
                                        <button type="button" wire:click="removeNewImage({{ $index }})"
                                            class="absolute top-0.5 right-0.5 px-1.5 py-0.5 text-xs text-white bg-red-500 rounded shadow hover:bg-red-600">
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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

    {{-- Image Preview Modal — with gallery navigation --}}
    <div id="expenseImageModal" class="img-modal-overlay">
        <button type="button" onclick="closeExpenseImageModal()" class="img-modal-close-btn" aria-label="Close">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Close
        </button>

        <button type="button" id="expenseImageModalPrev" onclick="showPrevExpenseImage(event)" class="img-modal-nav-btn img-modal-nav-left" aria-label="Previous image">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <img id="expenseImageModalImg" src="" class="img-modal-image">

        <button type="button" id="expenseImageModalNext" onclick="showNextExpenseImage(event)" class="img-modal-nav-btn img-modal-nav-right" aria-label="Next image">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div class="img-modal-footer">
            <span id="expenseImageModalCounter" class="img-modal-counter"></span>
            <p class="img-modal-hint">Use ← → to navigate · Tap outside or press Esc to close</p>
        </div>
    </div>

<style>
    /* ── Gallery Image Modal — vanilla CSS ─────────────────────────────── */
    .img-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 60;
        background: rgba(0, 0, 0, 0.92);
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .img-modal-overlay.is-open {
        display: flex;
    }
    .img-modal-image {
        max-width: 100%;
        max-height: 78vh;
        border-radius: 0.75rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        object-fit: contain;
        user-select: none;
    }
    .img-modal-close-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 5;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        background: #fff;
        color: #1f2937;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        border-radius: 9999px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        transition: background-color 0.15s, transform 0.1s;
    }
    .img-modal-close-btn:hover { background: #f3f4f6; }
    .img-modal-close-btn:active { transform: scale(0.95); }

    .img-modal-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        border: none;
        border-radius: 9999px;
        cursor: pointer;
        backdrop-filter: blur(4px);
        transition: background-color 0.15s, transform 0.1s;
    }
    .img-modal-nav-btn:hover { background: rgba(255, 255, 255, 0.25); }
    .img-modal-nav-btn:active { transform: translateY(-50%) scale(0.92); }
    .img-modal-nav-btn.is-hidden { display: none; }

    .img-modal-nav-left  { left: 0.75rem; }
    .img-modal-nav-right { right: 0.75rem; }

    @media (min-width: 640px) {
        .img-modal-nav-left  { left: 1.5rem; }
        .img-modal-nav-right { right: 1.5rem; }
        .img-modal-nav-btn { width: 3.5rem; height: 3.5rem; }
    }

    .img-modal-footer {
        margin-top: 0.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }
    .img-modal-counter {
        display: none;
        font-size: 0.75rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.85);
        background: rgba(255, 255, 255, 0.12);
        padding: 0.125rem 0.625rem;
        border-radius: 9999px;
    }
    .img-modal-counter.is-visible { display: inline-block; }
    .img-modal-hint {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.4);
        margin: 0;
    }
</style>

<script>
    // ── Gallery image modal (vanilla JS) ───────────────────────────────
    let expenseModalImages = [];
    let expenseModalIndex  = 0;

    function openExpenseImageModal(src, groupId) {
        expenseModalImages = [];

        const container = groupId ? document.getElementById(groupId) : null;
        if (container) {
            container.querySelectorAll('img').forEach(img => expenseModalImages.push(img.getAttribute('src')));
        }
        if (!expenseModalImages.length) {
            expenseModalImages = [src];
        }

        expenseModalIndex = expenseModalImages.indexOf(src);
        if (expenseModalIndex === -1) expenseModalIndex = 0;

        updateExpenseModalImage();
        document.getElementById('expenseImageModal').classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function updateExpenseModalImage() {
        document.getElementById('expenseImageModalImg').src = expenseModalImages[expenseModalIndex];

        const counter = document.getElementById('expenseImageModalCounter');
        const prevBtn = document.getElementById('expenseImageModalPrev');
        const nextBtn = document.getElementById('expenseImageModalNext');

        if (expenseModalImages.length > 1) {
            counter.textContent = `${expenseModalIndex + 1} / ${expenseModalImages.length}`;
            counter.classList.add('is-visible');
            prevBtn.classList.remove('is-hidden');
            nextBtn.classList.remove('is-hidden');
        } else {
            counter.classList.remove('is-visible');
            prevBtn.classList.add('is-hidden');
            nextBtn.classList.add('is-hidden');
        }
    }

    function showPrevExpenseImage(e) {
        if (e) e.stopPropagation();
        if (expenseModalImages.length < 2) return;
        expenseModalIndex = (expenseModalIndex - 1 + expenseModalImages.length) % expenseModalImages.length;
        updateExpenseModalImage();
    }

    function showNextExpenseImage(e) {
        if (e) e.stopPropagation();
        if (expenseModalImages.length < 2) return;
        expenseModalIndex = (expenseModalIndex + 1) % expenseModalImages.length;
        updateExpenseModalImage();
    }

    function closeExpenseImageModal() {
        document.getElementById('expenseImageModal').classList.remove('is-open');
        document.getElementById('expenseImageModalImg').src = '';
        expenseModalImages = [];
        expenseModalIndex = 0;
        document.body.style.overflow = '';
    }

    document.getElementById('expenseImageModal').addEventListener('click', e => {
        if (e.target === e.currentTarget) closeExpenseImageModal();
    });

    document.addEventListener('keydown', e => {
        if (!document.getElementById('expenseImageModal').classList.contains('is-open')) return;
        if (e.key === 'ArrowLeft') showPrevExpenseImage();
        if (e.key === 'ArrowRight') showNextExpenseImage();
        if (e.key === 'Escape') closeExpenseImageModal();
    });
</script>
</div>
