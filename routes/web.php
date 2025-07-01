<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Social Authentication Routes (optional)
Route::get('/auth/google', function () {
    // Implement Google OAuth logic
    return redirect()->back()->with('info', 'Google authentication not implemented yet.');
})->name('auth.google');

Route::get('/auth/apple', function () {
    // Implement Apple OAuth logic
    return redirect()->back()->with('info', 'Apple authentication not implemented yet.');
})->name('auth.apple');

Route::get('/auth/facebook', function () {
    // Implement Facebook OAuth logic
    return redirect()->back()->with('info', 'Facebook authentication not implemented yet.');
})->name('auth.facebook');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');