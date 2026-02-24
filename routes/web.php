<?php

use App\Http\Controllers\DataInputImageController;
use App\Http\Controllers\DataInputManagementController;
use App\Http\Controllers\ProfileController;
use App\Livewire\BoostTypeManagement;
use App\Livewire\DataInputForm;
use App\Livewire\UserManagement;
use App\Livewire\Report;
use App\Livewire\UserForm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (DataInput list)
    Route::get('/dashboard', [DataInputManagementController::class, 'index'])->name('dashboard');

    // DataInput actions
    Route::delete('data-inputs/{id}/delete', [DataInputManagementController::class, 'delete'])->name('data-inputs.delete');
    Route::post('data-inputs/{id}/copy',     [DataInputManagementController::class, 'copy'])->name('data-inputs.copy');
    Route::get('data-inputs/{id}/export',    [DataInputManagementController::class, 'export'])->name('data-inputs.export');
    Route::get('data-inputs/export-database',[DataInputManagementController::class, 'exportDatabase'])->name('data-inputs.export-db');

    // DataInput create/edit (Livewire)
    Route::get('data-inputs/create',              DataInputForm::class)->name('data-inputs.create');
    Route::get('data-inputs/{dataInputId}/edit',  DataInputForm::class)->name('data-inputs.edit');

    // Image upload/delete
    Route::post('data-inputs/{id}/image/{type}',   [DataInputImageController::class, 'upload'])->name('data-inputs.image.upload');
    Route::delete('data-inputs/{id}/image/{type}', [DataInputImageController::class, 'delete'])->name('data-inputs.image.delete');

    // Users
    Route::get('users',                UserManagement::class)->name('users.index');
    Route::get('users/create',         UserForm::class)->name('users.create');
    Route::get('users/{userId}/edit',  UserForm::class)->name('users.edit');

    // Report & Service types
    Route::get('/report',         Report::class)->name('report');
    Route::get('/services-types', BoostTypeManagement::class)->name('boost_types');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
