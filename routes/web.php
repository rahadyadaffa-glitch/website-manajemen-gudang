<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isSuperadmin()) {
        return redirect()->route('superadmin.dashboard');
    }

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function (): void {
    Route::get('/dashboard', [\App\Http\Controllers\Superadmin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/minimarkets/{minimarket}/trend', [\App\Http\Controllers\Superadmin\MinimarketController::class, 'trend'])->name('minimarkets.trend');
    Route::resource('minimarkets', \App\Http\Controllers\Superadmin\MinimarketController::class);
    Route::resource('admins', \App\Http\Controllers\Superadmin\AdminController::class);
    Route::get('/reports', [\App\Http\Controllers\Superadmin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/audit-logs', [\App\Http\Controllers\Superadmin\AuditLogController::class, 'index'])->name('audit.index');
});

Route::middleware(['auth', 'role:admin', 'minimarket.access'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::get('/users', fn () => 'Manage Users')->name('users.index');
    Route::get('/inventory', fn () => 'Inventory Approval')->name('inventory.index');
    Route::get('/reports', fn () => 'Store Reports')->name('reports.index');
});

Route::middleware(['auth', 'role:user', 'minimarket.access'])->prefix('user')->name('user.')->group(function (): void {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/input-barang-masuk', [\App\Http\Controllers\User\InventoryInputController::class, 'create'])->name('input.masuk.create');
    Route::post('/input-barang-masuk', [\App\Http\Controllers\User\InventoryInputController::class, 'store'])->name('input.masuk.store');
    Route::get('/input-barang-keluar', [\App\Http\Controllers\User\InventoryInputController::class, 'createKeluar'])->name('input.keluar.create');
    Route::post('/input-barang-keluar', [\App\Http\Controllers\User\InventoryInputController::class, 'storeKeluar'])->name('input.keluar.store');
    Route::get('/history', fn () => 'History')->name('history.index');
});
