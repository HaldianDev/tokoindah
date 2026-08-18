<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Storefront Routes
Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/katalog', [StoreController::class, 'catalog'])->name('store.index');
Route::get('/about', [StoreController::class, 'about'])->name('store.about');
Route::get('/product/{id}', [StoreController::class, 'show'])->name('store.product.show');

// Authenticated Dashboard Redirector
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isOwner()) {
            return redirect()->route('owner.dashboard');
        } else {
            return redirect()->route('customer.orders');
        }
    })->name('dashboard');

    // Customer Routes
    Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
    Route::get('/customer/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::post('/pay-installment/{id}', [OrderController::class, 'payInstallment'])->name('installment.pay');
    Route::post('/customer/order/{id}/upload-proof', [CustomerController::class, 'uploadCashProof'])->name('order.upload_cash_proof');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/product/store', [AdminController::class, 'storeProduct'])->name('product.store');
        Route::post('/stock/add', [AdminController::class, 'addStock'])->name('stock.add');
        Route::post('/order/status/{id}', [AdminController::class, 'updateOrderStatus'])->name('order.status');

        // Product CRUD
        Route::get('/product/{id}/edit', [AdminController::class, 'editProduct'])->name('product.edit');
        Route::put('/product/{id}', [AdminController::class, 'updateProduct'])->name('product.update');
        Route::delete('/product/{id}', [AdminController::class, 'destroyProduct'])->name('product.destroy');

        // Category CRUD
        Route::post('/category/store', [AdminController::class, 'storeCategory'])->name('category.store');
        Route::put('/category/{id}', [AdminController::class, 'updateCategory'])->name('category.update');
        Route::delete('/category/{id}', [AdminController::class, 'destroyCategory'])->name('category.destroy');

        // Web Settings
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
    });

    // Owner Routes
    Route::middleware(['role:owner'])->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
        Route::post('/order/status/{id}', [OwnerController::class, 'updateOrderStatus'])->name('order.status');
        Route::post('/installment/status/{id}', [OwnerController::class, 'updateInstallmentStatus'])->name('installment.status');

        // Export Laporan Excel & PDF
        Route::get('/export/excel', [OwnerController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [OwnerController::class, 'exportPdf'])->name('export.pdf');

        // Web Settings (bisa diedit juga oleh Owner)
        Route::post('/settings', [OwnerController::class, 'updateSettings'])->name('settings.update');
    });
});
