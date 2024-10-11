<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dashboard2Controller;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfielController;
use App\Http\Controllers\ZiekmeldingController;
use App\Http\Controllers\VerlofAanvraagController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::middleware('auth')->group(function () {

    // Two-factor authentication routes
    Route::get('/2fa', [LoginController::class, 'show2faForm'])->name('2fa.show');
    Route::post('/2fa', [LoginController::class, 'verify2fa'])->name('2fa.verify');
    Route::post('/2fa/regenerate', [LoginController::class, 'regenerate2fa'])->name('2fa.regenerate');

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard 2 route (for testing)
    Route::get('/dashboard2', [Dashboard2Controller::class, 'index'])->name('dashboard2');

    // Profiel routes
    Route::get('/profiel', [ProfielController::class, 'show'])->name('profiel.show');
    Route::get('/profiel/bewerken', [ProfielController::class, 'edit'])->name('profiel.edit');
    Route::put('/profiel/update', [ProfielController::class, 'update'])->name('profiel.update');
    Route::put('/profiel/change-password', [ProfielController::class, 'changePassword'])->name('profiel.changePassword');

    // Ziekmelding routes
    Route::get('/ziekmelding', [ZiekmeldingController::class, 'create'])->name('ziekmelding.create');
    Route::post('/ziekmelding', [ZiekmeldingController::class, 'store'])->name('ziekmelding.submit');

    // Verlofaanvraag routes
    Route::get('/verlofaanvragen', [VerlofAanvraagController::class, 'create'])->name('verlofaanvragen.create');
    Route::post('/verlofaanvragen', [VerlofAanvraagController::class, 'store'])->name('verlofaanvragen.store');

});

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
