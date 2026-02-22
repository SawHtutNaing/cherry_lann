<?php

namespace App\Http\Controllers;

use App\Models\DataInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataInputImageController extends Controller
{
    public function upload(Request $request, $id, $type)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if (!in_array($type, ['client_side_image', 'service_side_image'])) {
            return response()->json(['error' => 'Invalid image type.'], 422);
        }

        $dataInput = DataInput::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Delete old image if exists
        if ($dataInput->$type) {
            Storage::disk('public')->delete($dataInput->$type);
        }

        $path = $request->file('image')->store('data-input-images', 'public');

        $dataInput->update([$type => $path]);

        return response()->json([
            'url' => Storage::disk('public')->url($path),
        ]);
    }

    public function delete($id, $type)
    {
        if (!in_array($type, ['client_side_image', 'service_side_image'])) {
            return response()->json(['error' => 'Invalid image type.'], 422);
        }

        $dataInput = DataInput::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($dataInput->$type) {
            Storage::disk('public')->delete($dataInput->$type);
            $dataInput->update([$type => null]);
        }

        return response()->json(['success' => true]);
    }
}
