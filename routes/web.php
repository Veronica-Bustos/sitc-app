<?php

use App\Http\Controllers\Catalog\CategoryController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Logistics\LocationController;
use App\Http\Controllers\Logistics\MovementController;
use App\Http\Controllers\Maintenance\MaintenanceController;
use App\Http\Controllers\Media\AttachmentController;
use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->whereIn('locale', ['en', 'es']);

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');

    Route::resource('categories', CategoryController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('items', ItemController::class);
    Route::get('items/{item}/history', [ItemController::class, 'history'])->name('items.history');
    Route::resource('inventory-movements', MovementController::class);
    Route::resource('maintenance-records', MaintenanceController::class);
    Route::resource('attachments', AttachmentController::class);
});

require __DIR__.'/auth.php';
