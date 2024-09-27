<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController; // Import the controller

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Route to handle the login form submission
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Route to handle logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected dashboard route
Route::get('/dashboard', function () {
    return view('dashboard'); // Replace with your actual dashboard view
})->middleware('auth');

