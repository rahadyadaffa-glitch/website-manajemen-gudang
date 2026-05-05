<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::middleware(['guest', 'throttle:10,1'])->group(function (): void {
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
})->middleware(['auth', 'throttle:60,1'])->name('dashboard');

Route::middleware(['auth', 'role:superadmin', 'throttle:60,1'])->prefix('superadmin')->name('superadmin.')->group(function (): void {
    Route::get('/dashboard', [\App\Http\Controllers\Superadmin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/minimarkets/{minimarket}/trend', [\App\Http\Controllers\Superadmin\MinimarketController::class, 'trend'])->name('minimarkets.trend');
    Route::get('/minimarkets/{minimarket}/transactions', [\App\Http\Controllers\Superadmin\MinimarketController::class, 'transactions'])->name('minimarkets.transactions');
    Route::resource('minimarkets', \App\Http\Controllers\Superadmin\MinimarketController::class);
    Route::resource('admins', \App\Http\Controllers\Superadmin\AdminController::class);
    Route::resource('products', \App\Http\Controllers\Superadmin\ProductController::class);
    Route::get('/reports', [\App\Http\Controllers\Superadmin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/audit-logs', [\App\Http\Controllers\Superadmin\AuditLogController::class, 'index'])->name('audit.index');
});

Route::middleware(['auth', 'role:admin', 'minimarket.access', 'throttle:60,1'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    
    // Approval Routes
    Route::get('/approvals', [\App\Http\Controllers\Admin\ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('/approvals/{transaction}/approve', [\App\Http\Controllers\Admin\ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{transaction}/reject', [\App\Http\Controllers\Admin\ApprovalController::class, 'reject'])->name('approvals.reject');

    // User Management Routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Audit Trail Routes
    Route::get('/audit-trail', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit.index');
});

Route::middleware(['auth', 'role:user', 'minimarket.access', 'throttle:60,1'])->prefix('user')->name('user.')->group(function (): void {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/inventory/create/{type}', [\App\Http\Controllers\User\InventoryInputController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [\App\Http\Controllers\User\InventoryInputController::class, 'store'])->name('inventory.store');
    Route::get('/history', [\App\Http\Controllers\User\InventoryInputController::class, 'history'])->name('history.index');
    Route::get('/api/products', [\App\Http\Controllers\User\InventoryInputController::class, 'getProducts'])->name('api.products');
    Route::get('/api/products/{product}/variants', [\App\Http\Controllers\User\InventoryInputController::class, 'getVariants'])->name('api.products.variants');
});
