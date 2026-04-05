<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewArrivalsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES - Frontend
// ============================================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Search Products
Route::get('/search', [HomeController::class, 'search'])->name('home.search');

// View Product Details
Route::get('/products/{id}', [HomeController::class, 'show'])->name('products.show');

// View New Arrival Details
Route::get('/new-arrivals/{newArrival}', [HomeController::class, 'showNewArrival'])->name('new-arrivals.show');

// View Collection Products
Route::get('/collection/{collection}', [CollectionController::class, 'show'])->name('collection.products');

// ============================================
// CART ROUTES
// ============================================

// routes/web.php

Route::prefix('cart')->name('cart.')->middleware('web')->group(function () {
    Route::get('/', [CartController::class, 'view'])->name('view');
    Route::post('add/{productId}', [CartController::class, 'add'])->name('add');
    Route::post('update/{cartKey}', [CartController::class, 'update'])->name('update');
    Route::delete('remove/{cartKey}', [CartController::class, 'remove'])->name('remove');
    Route::post('clear', [CartController::class, 'clear'])->name('clear');
    Route::post('checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('filter-wilayah', [CartController::class, 'view'])->name('filter-wilayah');
    Route::get('/ajax/regencies/{provinceCode}', [CartController::class, 'ajaxRegencies'])->name('ajax.regencies');
Route::get('/ajax/districts/{regencyCode}', [CartController::class, 'ajaxDistricts'])->name('ajax.districts');
});
Route::prefix('order')->group(function () {
    Route::get('lacak', [OrderController::class, 'trackPage'])->name('order.track.page');
    Route::post('lacak', [OrderController::class, 'track'])->name('order.track');
    Route::get('{invoiceNumber}', [OrderController::class, 'show'])->name('order.show');
    Route::post('{invoiceNumber}/refresh-payment', [OrderController::class, 'refreshPayment'])->name('order.refresh-payment');
    Route::post('{invoiceNumber}/regenerate-payment', [OrderController::class, 'regeneratePayment'])->name('order.regenerate-payment');
    });
    Route::post('/midtrans-notification', [OrderController::class, 'handleNotification'])
        ->name('midtrans.notification')
        ->withoutMiddleware('csrf');
        Route::get('order/{invoiceNumber}/status', [OrderController::class, 'status'])
    ->name('order.status');
// ============================================
// AUTHENTICATED ROUTES - User Profile
// ============================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// ADMIN ROUTES - Protected by 'auth' middleware
// ============================================

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');

    // Product Management
    Route::resource('products', ProductController::class);
    // GET    /admin/products              (index)
    // GET    /admin/products/create       (create)
    // POST   /admin/products              (store)
    // GET    /admin/products/{id}         (show)
    // GET    /admin/products/{id}/edit    (edit)
    // PUT    /admin/products/{id}         (update)
    // DELETE /admin/products/{id}         (destroy)
    Route::post('/products/{id}/duplicate', [ProductController::class, 'duplicate'])
    ->name('products.duplicate');
    // Collection Management
    Route::resource('collections', CollectionController::class);
    // GET    /admin/collections              (index)
    // GET    /admin/collections/create       (create)
    // POST   /admin/collections              (store)
    // GET    /admin/collections/{id}         (show)
    // GET    /admin/collections/{id}/edit    (edit)
    // PUT    /admin/collections/{id}         (update)
    // DELETE /admin/collections/{id}         (destroy)

    // New Arrivals Management
    Route::resource('newArrivals', NewArrivalsController::class);
    // GET    /admin/new-arrivals              (index)
    // GET    /admin/new-arrivals/create       (create)
    // POST   /admin/new-arrivals              (store)
    // GET    /admin/new-arrivals/{id}         (show)
    // GET    /admin/new-arrivals/{id}/edit    (edit)
    // PUT    /admin/new-arrivals/{id}         (update)
    // DELETE /admin/new-arrivals/{id}         (destroy)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/',                [OrderController::class, 'index'])->name('index');
        Route::get('/{id}',            [OrderController::class, 'adminshow'])->name('show');
        Route::patch('/{id}/status',   [OrderController::class, 'updateStatus'])->name('updateStatus'); // ✅ lebih spesifik, di atas
        Route::patch('/{id}',          [OrderController::class, 'update'])->name('update');             // ✅ generic, di bawah
    });
});


// Redirect /dashboard to /admin/dashboard
Route::get('/dashboard', function() {
    return redirect('/admin/dashboard');
})->middleware('auth');

// ============================================
// LARAVEL BREEZE AUTH ROUTES
// ============================================

require __DIR__.'/auth.php';
