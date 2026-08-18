<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\TwoFactorPasswordResetController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\FileController; // Add this
use Illuminate\Http\Request; // Added this line
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/private-files/{path}', [FileController::class, 'serve'])->name('file.serve')->middleware('auth');

// Two-Factor Password Reset
Route::get('/password/reset/two-factor', [TwoFactorPasswordResetController::class, 'show'])->name('password.reset.two-factor');
Route::post('/password/reset/two-factor', [TwoFactorPasswordResetController::class, 'verify'])->name('password.reset.two-factor.verify');

// Email Verification Routes
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::post('/email/verify', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('/captcha-reload', [VerificationController::class, 'reloadCaptcha'])->name('captcha.reload');

// Public Storefront Routes
Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/dashboard-demo', function () {
    return view('dashboard-showcase');
})->name('dashboard.demo');

Route::get('/dashboard-v2', function () {
    return view('dashboard-v2');
})->name('dashboard.v2');
Route::get('/katalog', [StoreController::class, 'catalog'])->name('store.index');
Route::get('/about', [StoreController::class, 'about'])->name('store.about');
Route::get('/product/{id}', [StoreController::class, 'show'])->name('store.product.show');

// Placeholder Cart Routes (assuming backend is handled)
Route::post('/cart/add', function (Request $request) {
    // Implement your backend logic here if not already handled
    // For now, simulating a success
    return response()->json(['message' => 'Item added to cart successfully.', 'product_id' => $request->product_id, 'quantity' => $request->quantity ?? 1]);
})->name('cart.add');

Route::get('/cart', function () {
    // This will eventually show the cart contents
    return view('cart.index'); // You might need to create 'resources/views/cart/index.blade.php'
})->name('cart.index');

// Authenticated Dashboard Redirector
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isOwner()) {
            return redirect()->route('owner.dashboard');
        } else { // For Customers
            if (empty($user->address)) {
                return redirect()->route('customer.settings');
            }
            return redirect()->route('home');
        }
    })->name('dashboard');

    // Customer Routes
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.process');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/order/{order}/upload-proof', [OrderController::class, 'uploadProof'])->name('order.upload_proof');
    Route::post('/order/{order}/upload-ktp', [OrderController::class, 'uploadKtp'])->name('order.upload_ktp');
    Route::get('/customer/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/customer/settings', [CustomerController::class, 'settings'])->name('customer.settings');
    Route::post('/customer/settings', [CustomerController::class, 'updateSettings'])->name('customer.settings.update');
    Route::post('/pay-installment/{id}', [OrderController::class, 'payInstallment'])->name('installment.pay');
    Route::post('/customer/order/{id}/upload-proof', [CustomerController::class, 'uploadCashProof'])->name('order.upload_cash_proof');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/product/store', [AdminController::class, 'storeProduct'])->name('product.store');
        Route::post('/stock/add', [AdminController::class, 'addStock'])->name('stock.add');
        Route::post('/order/status/{order}', [AdminController::class, 'updateOrderStatus'])->name('order.status');
        Route::post('/order/{order}/confirm-manual-payment', [AdminController::class, 'confirmManualPayment'])->name('order.confirm_manual_payment');

        // Product CRUD
        Route::get('/product/{product}/edit', [AdminController::class, 'editProduct'])->name('product.edit');
        Route::put('/product/{product}', [AdminController::class, 'updateProduct'])->name('product.update');
        Route::delete('/product/{product}', [AdminController::class, 'destroy'])->name('product.destroy');

        // Category CRUD
        Route::post('/category/store', [AdminController::class, 'storeCategory'])->name('category.store');
        Route::put('/category/{category}', [AdminController::class, 'updateCategory'])->name('category.update');
        Route::delete('/category/{category}', [AdminController::class, 'destroyCategory'])->name('category.destroy');

        // Web Settings
        Route::get('/web-settings', [AdminController::class, 'editWebSettings'])->name('web_settings');
        Route::post('/web-settings', [AdminController::class, 'updateWebSettings'])->name('web_settings.update');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

        // Forward credit request
        Route::post('/credit/forward/{order}', [AdminController::class, 'forwardCreditRequest'])->name('credit.forward');

        // Cashier POS
        Route::post('/cashier/checkout', [AdminController::class, 'cashierCheckout'])->name('cashier.checkout');

        // Payment Verification
        Route::post('/order/{order}/approve-payment', [AdminController::class, 'approvePayment'])->name('order.approve_payment');
        Route::post('/order/{order}/reject-payment', [AdminController::class, 'rejectPayment'])->name('order.reject_payment');

        // API
        Route::get('/api/categories/{id}/products', [AdminController::class, 'getProductsByCategory'])->name('api.category.products');
        Route::get('/api/products/{product}/edit', [AdminController::class, 'editProduct'])->name('api.product.edit');
    });

    // Owner Routes
    Route::middleware(['role:owner'])->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
        Route::get('/settings', [OwnerController::class, 'settings'])->name('settings');
        Route::post('/settings', [OwnerController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/admin', [OwnerController::class, 'updateAdminSettings'])->name('settings.admin.update');
        Route::post('/order/status/{order}', [OwnerController::class, 'updateOrderStatus'])->name('order.status');
        Route::post('/installment/status/{installment}', [OwnerController::class, 'updateInstallmentStatus'])->name('installment.status');

        // Export Laporan Excel & PDF
        Route::get('/export/excel', [OwnerController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [OwnerController::class, 'exportPdf'])->name('export.pdf');
    });
});
