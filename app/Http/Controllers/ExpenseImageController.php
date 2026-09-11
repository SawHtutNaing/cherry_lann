<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseImageController extends Controller
{
    public function upload(Request $request, Expense $expense)
    {
        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'image|max:5120', // 5MB each
        ]);

        $created = [];

        foreach ($request->file('images') as $file) {
            $path = $file->store('expenses', 'public');

            $image = $expense->images()->create(['image_path' => $path]);

            $created[] = ['id' => $image->id, 'url' => $image->url];
        }

        return response()->json(['images' => $created]);
    }

    // "Edit" a single slot — replaces the stored file for an existing ExpenseImage in place.
    public function update(Request $request, Expense $expense, ExpenseImage $image)
    {
        abort_if($image->expense_id !== $expense->id, 404);

        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $oldPath = $image->image_path;
        $path = $request->file('image')->store('expenses', 'public');

        $image->update(['image_path' => $path]);
        Storage::disk('public')->delete($oldPath);

        return response()->json(['id' => $image->id, 'url' => $image->url]);
    }

    public function destroy(Expense $expense, ExpenseImage $image)
    {
        abort_if($image->expense_id !== $expense->id, 404);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
