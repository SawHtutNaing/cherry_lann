
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
use App\Livewire\ExpenseCategoryManagement;
use App\Livewire\ServiceTypeManagement;
use App\Livewire\ExpenseManagement;



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


use App\Http\Controllers\CmsImageUploadController;
use App\Livewire\ImageManagement;
use App\Livewire\CategoryManagement;

Route::middleware(['auth'])->group(function () {

    // Categories
    Route::get('/categories', CategoryManagement::class)->name('categories.index');

    // CMS Images
    Route::get('/cms-images', ImageManagement::class)->name('cms-images.index');

    Route::post('/cms-images/upload', [CmsImageUploadController::class, 'upload'])
        ->name('cms-images.upload');

    Route::post('/cms-images/delete-file', [CmsImageUploadController::class, 'delete'])
        ->name('cms-images.delete-file');

});





use App\Livewire\SiteSettingManagement;

Route::middleware(['auth'])->group(function () {
    // ...existing routes
    Route::get('/settings', SiteSettingManagement::class)->name('settings.index');
});


use App\Livewire\UserProfitLogManagement;

// Add this inside your existing auth/verified middleware group, near the other user routes:
Route::middleware(['auth'])->group(function () {
Route::get('users/{user}/profit-logs', UserProfitLogManagement::class)->name('users.profit-logs');

    Route::get('/expense-cateogry', ExpenseCategoryManagement::class)->name('expense_category.index');
    Route::get('/service-types', ServiceTypeManagement::class)->name('service_type.index');
    Route::get('/expense-management', ExpenseManagement::class)->name('expense_management.index');




});



// routes/web.php
Route::get('/', function () {
    $categories = \App\Models\Category::query()
        ->with(['images' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
        ->orderBy('sort_order')
        ->get();

    $settings = \App\Models\SiteSetting::current();

    return view('welcome', compact('categories', 'settings'));
});



use App\Livewire\ProfitReport;

// Add inside your existing auth/verified middleware group:
Route::get('/profit-report', ProfitReport::class)->name('profit-report.index');



use App\Livewire\SiteProfitReport;

// Add inside your existing auth/verified middleware group:
Route::get('/site-profit-report', SiteProfitReport::class)->name('site-profit-report.index');
