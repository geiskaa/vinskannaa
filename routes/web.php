<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::guard('web')->check()) {
        return redirect()->route('dashboard');
    }

    if (Auth::guard('seller')->check()) {
        return redirect()->route('seller.dashboard');
    }

    return redirect()->route('login');
});
Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/auth/google', [SocialLoginController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);
    Route::get('/auth/facebook', [SocialLoginController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('/auth/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback']);

    Route::get('/register/seller', [AuthController::class, 'showRegistrationSellerForm'])->name('seller.register');
    Route::post('/register/seller', [AuthController::class, 'registerSeller'])->name('seller.register.submit');
});
Route::middleware(['auth:web'])->group(function () {

    // Dashboard & About
    Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard');
    Route::get('/about', fn() => view('about'))->name('about');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Favorites
    Route::post('/favorites/{product}/toggle', [ProductController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favorites/{id}', [OrderController::class, 'removeFavorite'])->name('favorites.remove');

    // Cart
    Route::post('/cart/{product}/toggle', [CartController::class, 'toggleCart'])->name('cart.toggle');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::patch('/cart/{cart}/quantity', [CartController::class, 'updateQuantity'])->name('cart.update.quantity');
    Route::delete('/cart/{cart}', [CartController::class, 'removeItem'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');

    // Profile
    Route::get('/edit/profle', [ProfileController::class, 'showEditProfile'])->name('profile.edit');
    Route::put('/edit/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/address/{address}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');
    Route::patch('/profile/address/{address}/primary', [ProfileController::class, 'setPrimaryAddress'])->name('profile.address.setPrimary');

    // Checkout
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/token', [OrderController::class, 'getSnapToken']);
    Route::post('/handle-payment-success', [OrderController::class, 'handlePaymentSuccess']);
    Route::post('/handle-payment-failed', [OrderController::class, 'handlePaymentFailed']);

    // Order
    Route::get('/pesanan-saya', [OrderController::class, 'pesananSaya'])->name('pesananSaya');
    Route::get('/pesanan-saya/{id}', [OrderController::class, 'detailPesanan'])->name('detailpesanan');
    Route::post('/pesanan-saya/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::put('/pesanan-saya/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/pesanan-saya/{id}/rate', [OrderController::class, 'showRatingForm'])->name('orders.rate');
    Route::post('/pesanan-saya/{id}/rate', [OrderController::class, 'storeRating'])->name('orders.rate.store');

    // Produk
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
});

Route::prefix('seller')->name('seller.')->middleware('auth:seller')->group(function () {

    // Dashboard
    Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/data', [SellerController::class, 'dashboardData'])->name('dashboard.data');

    // Produk
    Route::get('/all-produk', [SellerController::class, 'allProduk'])->name('all-produk');
    Route::get('/tambah-produk', [SellerController::class, 'create'])->name('tambah-produk');
    Route::post('/tambah-produk/post', [SellerController::class, 'store'])->name('tambah-produk.post');
    Route::get('/edit-produk/{id}', [SellerController::class, 'editProduk'])->name('edit-produk');
    Route::put('/edit-produk/{product}', [SellerController::class, 'update'])->name('update-produk');
    Route::get('/detail-produk/{slug}', [SellerController::class, 'detailProduk'])->name('detail-produk');

    // Pesanan
    Route::get('/list-pesanan', [SellerController::class, 'listPesanan'])->name('list-pesanan');
    Route::get('/list-pesanan/{orderId}/detail', [SellerController::class, 'detailPesanan'])->name('pesanan.detail');
    Route::post('/list-pesanan/{orderId}/konfirmasi', [SellerController::class, 'konfirmasiPesanan'])->name('pesanan.konfirmasi');
    Route::post('/list-pesanan/{orderId}/proses', [SellerController::class, 'prosesPesanan'])->name('pesanan.proses');
    Route::post('/list-pesanan/{orderId}/kirim', [SellerController::class, 'kirimPesanan'])->name('pesanan.kirim');
    Route::get('/delete-produk/{id}', [SellerController::class, 'destroy'])->name('destroy');

    // Batch update status
    Route::post('/batch-update', [SellerController::class, 'updateStatusBatch'])->name('batch.update');

    // Ulasan
    Route::get('/list-ulasan', [SellerController::class, 'listUlasan'])->name('list-ulasan');
});
