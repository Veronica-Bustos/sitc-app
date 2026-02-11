<?php

use App\Http\Controllers\Catalog\CategoryController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Logistics\LocationController;
use App\Http\Controllers\Logistics\MovementController;
use App\Http\Controllers\Maintenance\MaintenanceController;
use App\Http\Controllers\Media\AttachmentController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\Settings\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->whereIn('locale', ['en', 'es']);

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'can:dashboard.view'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');

    Route::get('settings/users', [UserManagementController::class, 'index'])
        ->name('settings.users.index')
        ->middleware('can:manage,App\\Models\\User');
    Route::put('settings/users/{user}/role', [UserManagementController::class, 'updateUserRole'])
        ->name('settings.users.role.update')
        ->middleware('can:manage,App\\Models\\User');
    Route::put('settings/roles/{role}/permissions', [UserManagementController::class, 'updateRolePermissions'])
        ->name('settings.roles.permissions.update')
        ->middleware('can:manage,App\\Models\\User');

    Route::resource('categories', CategoryController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('items', ItemController::class);
    Route::get('items/{item}/history', [ItemController::class, 'history'])->name('items.history');
    Route::resource('inventory-movements', MovementController::class);
    Route::resource('maintenance-records', MaintenanceController::class);
    Route::resource('attachments', AttachmentController::class);
    Route::get('attachments/{attachment}/download', [AttachmentController::class, 'download'])
        ->name('attachments.download');
    Route::get('attachments/{attachment}/preview', [AttachmentController::class, 'preview'])
        ->name('attachments.preview');
});

require __DIR__ . '/auth.php';
