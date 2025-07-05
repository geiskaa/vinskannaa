<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/auth/google', [SocialLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [SocialLoginController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback']);

Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/about', function () {
    return view('about');
})->name('about')->middleware('auth');

Route::post('/favorites/{product}/toggle', [ProductController::class, 'toggle'])->name('favorites.toggle');

Route::middleware(['auth'])->group(function () {

    // Toggle cart (add/remove)
    Route::post('/cart/{product}/toggle', [CartController::class, 'toggleCart'])->name('cart.toggle');

    // Tampilkan halaman cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    // Update quantity item di cart
    Route::patch('/cart/{cart}/quantity', [CartController::class, 'updateQuantity'])->name('cart.update.quantity');

    // Hapus item dari cart
    Route::delete('/cart/{cart}', [CartController::class, 'removeItem'])->name('cart.remove');

    // Clear semua items di cart
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

});

Route::get('/edit/profle', [ProfileController::class, 'showEditProfile'])->name('profile.edit');
Route::put('/edit/update', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile/address/{address}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');
Route::patch('/profile/address/{address}/primary', [ProfileController::class, 'setPrimaryAddress'])->name('profile.address.setPrimary');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout/token', [OrderController::class, 'getSnapToken']);
Route::get('/search', [ProductController::class, 'search'])->name('search');

Route::middleware('auth')->group(function () {

    // Halaman pesanan saya dan wishlist
    Route::get('/pesanan-saya', [OrderController::class, 'pesananSaya'])->name('pesananSaya');

    // Detail pesanan
    Route::get('/pesanan-saya/{id}', [OrderController::class, 'detailPesanan'])->name('detailpesanan');

    // Batalkan pesanan
    Route::post('/pesanan-saya/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Hapus dari wishlist/favorites
    Route::delete('/favorites/{id}', [OrderController::class, 'removeFavorite'])->name('favorites.remove');

    // Tambah ke keranjang dari wishlist
    Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');

    // Rating pesanan
    Route::get('/pesanan-saya/{id}/rate', action: [OrderController::class, 'showRatingForm'])->name('orders.rate');
    Route::post('/pesanan-saya/{id}/rate', [OrderController::class, 'storeRating'])->name('orders.rate.store');

});

Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/handle-payment-success', [OrderController::class, 'handlePaymentSuccess']);
Route::post('/handle-payment-failed', [OrderController::class, 'handlePaymentFailed']);
Route::put('/pesanan-saya/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

Route::prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/data', [SellerController::class, 'dashboardData'])->name('seller.dashboard.data');

    Route::get('/all-produk', [SellerController::class, 'allProduk'])->name('all-produk');
    Route::get('/list-pesanan', [SellerController::class, 'listPesanan'])->name('list-pesanan');
    Route::get('/list-ulasan', [SellerController::class, 'listUlasan'])->name('list-ulasan');
    Route::get('/detail-produk/{slug}', [SellerController::class, 'detailProduk'])->name('detail-produk');
    Route::get('/tambah-produk', [SellerController::class, 'create'])->name('tambah-produk');
    Route::post('/tambah-produk/post', [SellerController::class, 'store'])->name('tambah-produk.post');
    Route::get('/edit-produk/{id}', [SellerController::class, 'editProduk'])->name('edit-produk');
    Route::put('/edit-produk/{product}', [SellerController::class, 'update'])->name('products.update');
    Route::get('/delete-produk/{id}', [SellerController::class, 'deleteProduk'])->name('edit-produk');
    Route::get('/delete-produk/{id}', [SellerController::class, 'deleteProduk'])->name('edit-produk');

    Route::post('/list-pesanan/{orderId}/konfirmasi', [SellerController::class, 'konfirmasiPesanan'])->name('konfirmasi');
    Route::post('/list-pesanan/{orderId}/proses', [SellerController::class, 'prosesPesanan'])->name('proses');
    Route::post('/list-pesanan/{orderId}/kirim', [SellerController::class, 'kirimPesanan'])->name('kirim');

    // Batch update status
    Route::post('batch-update', [SellerController::class, 'updateStatusBatch'])->name('batch.update');

    // Detail pesanan
    Route::get('/list-pesanan/{orderId}/detail', [SellerController::class, 'detailPesanan'])->name('detail');

});

Route::get('/register/seller', [AuthController::class, 'showRegistrationSellerForm'])->name('seller.register');
Route::post('/register/seller', [AuthController::class, 'registerSeller'])->name('seller.register.submit');
