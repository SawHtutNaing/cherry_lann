<x-app-layout>
<div class="container mx-auto mt-8 px-4">
    @if (session('success'))
        <div class="p-4 mb-4 text-green-800 bg-green-100 rounded">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Visas</h1>
        <a href="{{ route('visas.create') }}"
            class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-400">
            Create New Visa
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-100 border-b">
                    <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">No</th>
                    <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">User</th>
                    <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Date</th>
                    <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Remark</th>
                    <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Images</th>
                    <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Created At</th>
                    <th class="px-6 py-3 text-sm font-bold text-center text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($visas as $index => $visa)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $visas->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $visa->user->name ?? 'N/A' }}</td>
                        {{-- Money-formatted display; DB column stays a plain integer --}}
                        <td class="px-6 py-4 text-sm font-semibold text-center text-gray-800">{{ number_format($visa->amount) }} Ks</td>
                        <td class="px-6 py-4 text-sm text-center text-gray-800">
                            {{ $visa->date ? $visa->date->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-center text-gray-600 max-w-xs">
                            @if ($visa->remark)
                                <span class="block truncate" title="{{ $visa->remark }}">{{ $visa->remark }}</span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-wrap gap-1 justify-center" id="visa-images-{{ $visa->id }}">
                                @forelse ($visa->images as $image)
                                    <img src="{{ $image->url }}"
                                        class="w-10 h-10 object-cover rounded border border-gray-200 cursor-pointer hover:opacity-80"
                                        onclick="openVisaImageModal('{{ $image->url }}', 'visa-images-{{ $visa->id }}')">
                                @empty
                                    <span class="text-xs text-gray-400">No images</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-center text-gray-500">{{ $visa->created_at->format('d/m/y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col gap-2 sm:flex-row justify-center">
                                <a href="{{ route('visas.edit', $visa->id) }}"
                                    class="w-full sm:w-20 px-3 py-2 text-sm text-center text-white bg-yellow-500 rounded shadow hover:bg-yellow-400">Edit</a>
                                <form method="POST" action="{{ route('visas.destroy', $visa->id) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this visa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full sm:w-20 px-3 py-2 text-sm text-center text-white bg-red-500 rounded shadow hover:bg-red-400">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-sm text-center text-gray-400">No visas found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $visas->links() }}</div>

    {{-- Image Preview Modal — with gallery navigation --}}
    <div id="visaImageModal" class="img-modal-overlay">
        <button type="button" onclick="closeVisaImageModal()" class="img-modal-close-btn" aria-label="Close">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Close
        </button>

        <button type="button" id="visaImageModalPrev" onclick="showPrevVisaImage(event)" class="img-modal-nav-btn img-modal-nav-left" aria-label="Previous image">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <img id="visaImageModalImg" src="" class="img-modal-image">

        <button type="button" id="visaImageModalNext" onclick="showNextVisaImage(event)" class="img-modal-nav-btn img-modal-nav-right" aria-label="Next image">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div class="img-modal-footer">
            <span id="visaImageModalCounter" class="img-modal-counter"></span>
            <p class="img-modal-hint">Use ← → to navigate · Tap outside or press Esc to close</p>
        </div>
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
    let visaModalImages = [];
    let visaModalIndex  = 0;

    function openVisaImageModal(src, groupId) {
        visaModalImages = [];

        const container = groupId ? document.getElementById(groupId) : null;
        if (container) {
            container.querySelectorAll('img').forEach(img => visaModalImages.push(img.getAttribute('src')));
        }
        if (!visaModalImages.length) {
            visaModalImages = [src];
        }

        visaModalIndex = visaModalImages.indexOf(src);
        if (visaModalIndex === -1) visaModalIndex = 0;

        updateVisaModalImage();
        document.getElementById('visaImageModal').classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function updateVisaModalImage() {
        document.getElementById('visaImageModalImg').src = visaModalImages[visaModalIndex];

        const counter = document.getElementById('visaImageModalCounter');
        const prevBtn = document.getElementById('visaImageModalPrev');
        const nextBtn = document.getElementById('visaImageModalNext');

        if (visaModalImages.length > 1) {
            counter.textContent = `${visaModalIndex + 1} / ${visaModalImages.length}`;
            counter.classList.add('is-visible');
            prevBtn.classList.remove('is-hidden');
            nextBtn.classList.remove('is-hidden');
        } else {
            counter.classList.remove('is-visible');
            prevBtn.classList.add('is-hidden');
            nextBtn.classList.add('is-hidden');
        }
    }

    function showPrevVisaImage(e) {
        if (e) e.stopPropagation();
        if (visaModalImages.length < 2) return;
        visaModalIndex = (visaModalIndex - 1 + visaModalImages.length) % visaModalImages.length;
        updateVisaModalImage();
    }

    function showNextVisaImage(e) {
        if (e) e.stopPropagation();
        if (visaModalImages.length < 2) return;
        visaModalIndex = (visaModalIndex + 1) % visaModalImages.length;
        updateVisaModalImage();
    }

    function closeVisaImageModal() {
        document.getElementById('visaImageModal').classList.remove('is-open');
        document.getElementById('visaImageModalImg').src = '';
        visaModalImages = [];
        visaModalIndex = 0;
        document.body.style.overflow = '';
    }

    document.getElementById('visaImageModal').addEventListener('click', e => {
        if (e.target === e.currentTarget) closeVisaImageModal();
    });

    document.addEventListener('keydown', e => {
        if (!document.getElementById('visaImageModal').classList.contains('is-open')) return;
        if (e.key === 'ArrowLeft') showPrevVisaImage();
        if (e.key === 'ArrowRight') showNextVisaImage();
        if (e.key === 'Escape') closeVisaImageModal();
    });
</script>
</x-app-layout>
