<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\BoostTypeManagement;
use App\Livewire\DataInputForm;
use App\Livewire\DataInputManagement;
use App\Livewire\UserManagement;
use Illuminate\Support\Facades\Route;
use App\Livewire\DateInputForm;
use App\Livewire\Report;
use App\Livewire\UserForm;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('users', UserManagement::class)->name('users.index');
    Route::get('users/create', UserForm::class)->name('users.create');
    Route::get('users/{userId}/edit', UserForm::class)->name('users.edit');
    Route::get('/dashboard', DataInputManagement::class)->name('dashboard');
    Route::get('data-inputs/create', DataInputForm::class)->name('data-inputs.create');
    Route::get('data-inputs/{dataInputId}/edit', DataInputForm::class)->name('data-inputs.edit');
    Route::get('/report', Report::class)->name('report');
    Route::get('/services-types' , BoostTypeManagement::class)->name('boost_types');

});
Route::get('users', UserManagement::class)->name('users.index');





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
