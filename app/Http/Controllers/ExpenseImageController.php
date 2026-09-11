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

    public function destroy(Expense $expense, ExpenseImage $image)
    {
        abort_if($image->expense_id !== $expense->id, 404);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
