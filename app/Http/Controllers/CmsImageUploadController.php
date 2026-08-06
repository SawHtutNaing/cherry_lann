<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsImageUploadController extends Controller
{
    /**
     * Upload a file and return its path/url.
     * NOTE: this endpoint only ever stores a new file — it must never delete
     * an existing image. Deleting the old file is the Livewire component's
     * job, and only happens once the record has actually been saved with
     * the new path (see ImageManagement::save()).
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $path = $request->file('image')->store('cms-images', 'public');

        return response()->json([
            'success' => true,
            'path'    => $path,
            'url'     => Storage::disk('public')->url($path),
        ]);
    }

    /**
     * Delete a file that was uploaded but never saved to a record
     * (kept for optional client-side cleanup use).
     */
    public function delete(Request $request)
    {
        $request->validate(['path' => 'required|string']);

        Storage::disk('public')->delete($request->path);

        return response()->json(['success' => true]);
    }
}
