<?php

namespace App\Http\Controllers;

use App\Models\Visa;
use App\Models\VisaImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VisaController extends Controller
{
    public function index()
    {
        $visas = Visa::with(['user', 'images'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('visas.index', compact('visas'));
    }

    public function create()
    {
        return view('visas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount'    => 'required|integer|min:0',
            'date'      => 'required|date',
            'remark'    => 'nullable|string|max:2000',
            'images'    => 'nullable|array',
            'images.*'  => 'image|max:5120', // 5MB each
        ]);

        $visa = Visa::create([
            'user_id' => Auth::id(),
            'amount'  => $validated['amount'],
            'date'    => $validated['date'],
            'remark'  => $validated['remark'] ?? null,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('visas', 'public');
                $visa->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('visas.index')->with('success', 'Visa created successfully.');
    }

    public function edit(Visa $visa)
    {
        $visa->load('images');
        return view('visas.edit', compact('visa'));
    }

    public function update(Request $request, Visa $visa)
    {
        $validated = $request->validate([
            'amount'    => 'required|integer|min:0',
            'date'      => 'required|date',
            'remark'    => 'nullable|string|max:2000',
            'images'    => 'nullable|array',
            'images.*'  => 'image|max:5120',
        ]);

        $visa->update([
            'amount' => $validated['amount'],
            'date'   => $validated['date'],
            'remark' => $validated['remark'] ?? null,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('visas', 'public');
                $visa->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('visas.index')->with('success', 'Visa updated successfully.');
    }

    public function destroy(Visa $visa)
    {
        foreach ($visa->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $visa->delete(); // visa_images rows cascade-delete via FK

        return redirect()->back()->with('success', 'Visa deleted successfully.');
    }

    // ── AJAX: delete a single image without touching the rest of the form ──
    public function destroyImage(Visa $visa, VisaImage $image)
    {
        abort_if($image->visa_id !== $visa->id, 404);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
