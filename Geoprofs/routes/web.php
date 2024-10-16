<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dashboard2Controller;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfielController;
use App\Http\Controllers\UserCreateController;
use App\Http\Controllers\VerlofAanvraagController;
use App\Http\Controllers\KeuringController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZiekmeldenController;


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
    Route::get('/ziekmelden', [ZiekmeldenController::class, 'index'])->name('ziekmelden.index');
    Route::post('/ziekmelden', [ZiekmeldenController::class, 'store'])->name('ziekmelden.store');

    // Add users routes
    Route::get('/addusers', [UserCreateController::class, 'index'])->name('addusers.index');
    Route::post('/addusers', [UserCreateController::class, 'store'])->name('addusers.store');

    // Verlofaanvraag routes
    Route::get('/verlofaanvragen', [VerlofAanvraagController::class, 'create'])->name('verlofaanvragen.create');
    Route::post('/verlofaanvragen', [VerlofAanvraagController::class, 'store'])->name('verlofaanvragen.store');

    Route::middleware('auth')->group(function () {
        Route::get('/keuring', [KeuringController::class, 'index'])->name('keuring.index');
        Route::post('/keuring/update-status/{id}', [KeuringController::class, 'updateStatus'])->name('keuring.updateStatus');
    });

});

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
