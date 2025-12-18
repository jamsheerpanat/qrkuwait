<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'super_admin') {
        return redirect()->route('super.dashboard');
    } elseif ($user->role === 'tenant_admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('super')->group(function () {
    Route::get('/', function () {
        return view('super.dashboard');
    })->name('super.dashboard');
});

// Tenant Specific Routes (Admin/Staff)
Route::middleware(['auth', 'tenant'])->prefix('admin')->group(function () {
    // Tenant Admin Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->middleware('role:tenant_admin')->name('admin.dashboard');

    // Catalog Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('items', \App\Http\Controllers\Admin\ItemController::class);

    // QR Management
    Route::get('/qr', [\App\Http\Controllers\Admin\QRController::class, 'index'])->name('admin.qr.index');
    Route::get('/qr/download', [\App\Http\Controllers\Admin\QRController::class, 'download'])->name('admin.qr.download');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

    // Orders Management
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderManagerController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderManagerController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderManagerController::class, 'updateStatus'])->name('admin.orders.status');
    Route::get('/orders/{id}/print', [\App\Http\Controllers\Admin\OrderManagerController::class, 'print'])->name('admin.orders.print');

    // KDS & Packing
    Route::get('/kds', [\App\Http\Controllers\Admin\KDSController::class, 'index'])->middleware('role:kitchen,tenant_admin')->name('admin.kds.index');
    Route::get('/kds/feed', [\App\Http\Controllers\Admin\KDSController::class, 'feed'])->middleware('role:kitchen,tenant_admin')->name('admin.kds.feed');
    Route::get('/packing', [\App\Http\Controllers\Admin\KDSController::class, 'packing'])->middleware('role:kitchen,tenant_admin')->name('admin.packing.index');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->middleware('role:tenant_admin')->name('admin.reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->middleware('role:tenant_admin')->name('admin.reports.export');

    // Profile (shared across tenants)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API for POS (Token Based)
Route::get('/api/pos/orders', [\App\Http\Controllers\Api\POSController::class, 'index']);

// Public Tenant Routes
Route::middleware(['tenant'])->group(function () {
    Route::get('/{tenant_slug}', [\App\Http\Controllers\Public\MenuController::class, 'index'])->name('tenant.public');

    // Checkout Routes with Rate Limiting
    Route::get('/{tenant_slug}/checkout', [\App\Http\Controllers\Public\CheckoutController::class, 'index'])->name('tenant.checkout');
    Route::post('/{tenant_slug}/checkout', [\App\Http\Controllers\Public\CheckoutController::class, 'store'])
        ->middleware('throttle:5,1') // 5 orders per minute per IP
        ->name('tenant.checkout.store');
    Route::get('/{tenant_slug}/order/{order_no}/success', [\App\Http\Controllers\Public\CheckoutController::class, 'success'])->name('tenant.checkout.success');
});

require __DIR__ . '/auth.php';
