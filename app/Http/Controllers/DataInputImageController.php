<?php

namespace App\Http\Controllers;

use App\Models\DataInput;
use App\Models\DataInputImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataInputImageController extends Controller
{
    public function upload(Request $request, $id, $type)
    {
        abort_unless(in_array($type, ['client', 'service']), 404);

        $dataInput = DataInput::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'image|max:5120', // 5MB each
        ]);

        $created = [];

        foreach ($request->file('images') as $file) {
            $path = $file->store("data-inputs/{$type}", 'public');

            $image = $dataInput->images()->create([
                'type'       => $type,
                'image_path' => $path,
            ]);

            $created[] = ['id' => $image->id, 'url' => $image->url];
        }

        return response()->json(['images' => $created]);
    }

    public function delete($id, $imageId)
    {
        $dataInput = DataInput::where('user_id', auth()->id())->findOrFail($id);
        $image = DataInputImage::where('data_input_id', $dataInput->id)->findOrFail($imageId);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
