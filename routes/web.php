<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Redirect /dashboard based on user role
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $role = auth()->user()->role;
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'seller') {
        return redirect()->route('seller.dashboard');
    } else {
        return redirect()->route('buyer.dashboard');
    }
})->middleware(['auth'])->name('dashboard');

// Public catalog routes
Route::get('/', [ProductController::class, 'catalog'])->name('catalog');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/shops/{slug}', [ShopController::class, 'show'])->name('shops.show');

Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/voucher', [CartController::class, 'applyVoucher'])->name('cart.voucher');

    // Checkout Routes
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // Order placing and tracking
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/return', [OrderController::class, 'returnOrder'])->name('orders.return');

    // Payment upload confirmation
    Route::get('/payments/confirm/{order_id}', [PaymentController::class, 'create'])->name('payments.confirm');
    Route::post('/payments/confirm/{order_id}', [PaymentController::class, 'store'])->name('payments.store');

    // Review upload
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // --- BUYER DASHBOARD & LOGGED IN ONLY ---
    Route::middleware('role:buyer')->group(function () {
        Route::get('/buyer/dashboard', [OrderController::class, 'buyerDashboard'])->name('buyer.dashboard');
    });

    // --- SELLER DASHBOARD & MANAGEMENT ---
    Route::middleware('role:seller')->group(function () {
        Route::get('/seller/dashboard', [ShopController::class, 'sellerDashboard'])->name('seller.dashboard');
        
        // Shop Management
        Route::get('/seller/shop', [ShopController::class, 'edit'])->name('seller.shop.edit');
        Route::patch('/seller/shop', [ShopController::class, 'update'])->name('seller.shop.update');

        // Products CRUD
        Route::resource('/seller/products', ProductController::class)->names([
            'index' => 'seller.products.index',
            'create' => 'seller.products.create',
            'store' => 'seller.products.store',
            'edit' => 'seller.products.edit',
            'update' => 'seller.products.update',
            'destroy' => 'seller.products.destroy',
        ]);
        Route::post('/seller/products/{id}/variants', [ProductController::class, 'addVariant'])->name('seller.products.variants');
        Route::delete('/seller/variants/{id}', [ProductController::class, 'deleteVariant'])->name('seller.variants.destroy');

        // Seller Orders Management
        Route::get('/seller/orders', [OrderController::class, 'sellerOrders'])->name('seller.orders.index');
        Route::post('/seller/orders/{id}/process', [OrderController::class, 'processOrder'])->name('seller.orders.process');
        Route::post('/seller/orders/{id}/ship', [OrderController::class, 'shipOrder'])->name('seller.orders.ship');
        Route::post('/seller/orders/{id}/complete', [OrderController::class, 'completeOrder'])->name('seller.orders.complete');
        Route::post('/seller/payments/{id}/verify', [PaymentController::class, 'verifyPayment'])->name('seller.payments.verify');

        // Seller Vouchers Management
        Route::resource('/seller/vouchers', VoucherController::class)->names([
            'index' => 'seller.vouchers.index',
            'create' => 'seller.vouchers.create',
            'store' => 'seller.vouchers.store',
            'edit' => 'seller.vouchers.edit',
            'update' => 'seller.vouchers.update',
            'destroy' => 'seller.vouchers.destroy',
        ]);
    });

    // --- ADMIN SYSTEM MANAGEMENT ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::patch('/admin/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
        Route::get('/admin/reviews', [AdminController::class, 'reviews'])->name('admin.reviews.index');
        Route::post('/admin/reviews/{id}/moderate', [AdminController::class, 'moderateReview'])->name('admin.reviews.moderate');
    });

    // --- REPORT GENERATION (PDF & EXCEL) ---
    // PDF Reports
    Route::get('/reports/invoice/{id}', [ReportController::class, 'invoicePdf'])->name('reports.invoice');
    Route::get('/reports/surat-jalan/{id}', [ReportController::class, 'suratJalanPdf'])->name('reports.surat_jalan');
    Route::get('/reports/stock', [ReportController::class, 'stockPdf'])->name('reports.stock_pdf');

    // Excel Reports
    Route::get('/reports/stock/excel', [ReportController::class, 'stockExcel'])->name('reports.stock_excel');
    Route::get('/reports/sales-recap', [ReportController::class, 'salesRecapExcel'])->name('reports.sales_recap');
    Route::get('/reports/orders-buyers', [ReportController::class, 'ordersBuyersExcel'])->name('reports.orders_buyers');
});

require __DIR__.'/auth.php';
