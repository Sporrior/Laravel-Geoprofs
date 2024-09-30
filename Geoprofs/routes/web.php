<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

// Show the login form
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login submission
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Show the registration form
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');

// Handle the registration submission
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Handle logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Show the dashboard (Only for authenticated users)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');