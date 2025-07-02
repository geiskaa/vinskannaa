<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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

Route::get('/edit/profle', [AuthController::class, 'showEditProfile'])->name('profile.edit');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout/token', [OrderController::class, 'getSnapToken']);
Route::get('/search', [ProductController::class, 'search'])->name('search');