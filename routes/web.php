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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('super')->group(function () {
    Route::get('/', [\App\Http\Controllers\Super\DashboardController::class, 'index'])->name('super.dashboard');

    Route::resource('tenants', \App\Http\Controllers\Super\TenantController::class)->names('super.tenants');
    Route::resource('users', \App\Http\Controllers\Super\UserController::class)->names('super.users');
});


// Tenant Specific Routes (Admin/Staff)
Route::middleware(['auth', 'tenant'])->prefix('admin')->name('admin.')->group(function () {
    // Tenant Admin Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->middleware('role:tenant_admin')->name('dashboard');

    // Catalog Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('items', \App\Http\Controllers\Admin\ItemController::class);

    // QR Management
    Route::get('/qr', [\App\Http\Controllers\Admin\QRController::class, 'index'])->name('qr.index');
    Route::get('/qr/download', [\App\Http\Controllers\Admin\QRController::class, 'download'])->name('qr.download');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Orders Management
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderManagerController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderManagerController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderManagerController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{id}/print', [\App\Http\Controllers\Admin\OrderManagerController::class, 'print'])->name('orders.print');

    // KDS & Packing
    Route::get('/kds', [\App\Http\Controllers\Admin\KDSController::class, 'index'])->middleware('role:kitchen,tenant_admin')->name('kds.index');
    Route::get('/kds/feed', [\App\Http\Controllers\Admin\KDSController::class, 'feed'])->middleware('role:kitchen,tenant_admin')->name('kds.feed');
    Route::get('/packing', [\App\Http\Controllers\Admin\KDSController::class, 'packing'])->middleware('role:kitchen,tenant_admin')->name('packing.index');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->middleware('role:tenant_admin')->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->middleware('role:tenant_admin')->name('reports.export');

    // POS System
    Route::get('/pos', [\App\Http\Controllers\Admin\POSController::class, 'index'])->name('pos.index');
    Route::get('/pos/items', [\App\Http\Controllers\Admin\POSController::class, 'items'])->name('pos.items');
    Route::get('/pos/pending', [\App\Http\Controllers\Admin\POSController::class, 'pendingOrders'])->name('pos.pending');
    Route::get('/pos/count', [\App\Http\Controllers\Admin\POSController::class, 'orderCount'])->name('pos.count');
    Route::post('/pos/create', [\App\Http\Controllers\Admin\POSController::class, 'createOrder'])->name('pos.create');
    Route::post('/pos/accept/{id}', [\App\Http\Controllers\Admin\POSController::class, 'acceptOrder'])->name('pos.accept');
    Route::post('/pos/status/{id}', [\App\Http\Controllers\Admin\POSController::class, 'updateOrderStatus'])->name('pos.status');
    Route::get('/pos/receipt/{id}', [\App\Http\Controllers\Admin\POSController::class, 'printReceipt'])->name('pos.receipt');

});

// API for POS (Token Based)
Route::get('/api/pos/orders', [\App\Http\Controllers\Api\POSController::class, 'index']);

require __DIR__ . '/auth.php';

// Public Tenant Routes
Route::middleware(['tenant'])->group(function () {
    Route::get('/{tenant_slug}', [\App\Http\Controllers\Public\MenuController::class, 'index'])->name('tenant.public');
    
    // Checkout Routes with Rate Limiting
    Route::get('/{tenant_slug}/checkout', [\App\Http\Controllers\Public\CheckoutController::class, 'index'])->name('tenant.checkout');
    Route::post('/{tenant_slug}/checkout', [\App\Http\Controllers\Public\CheckoutController::class, 'store'])
        ->middleware('throttle:5,1') // 5 orders per minute per IP
        ->name('tenant.checkout.store');
    Route::get('/{tenant_slug}/order/{order_no}/success', [\App\Http\Controllers\Public\CheckoutController::class, 'success'])->name('tenant.checkout.success');
    Route::post('/{tenant_slug}/order/{order_no}/upload-payment', [\App\Http\Controllers\Public\CheckoutController::class, 'uploadPayment'])->name('tenant.order.upload-payment');
});
