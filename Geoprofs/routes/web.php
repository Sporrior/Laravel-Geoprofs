<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    Dashboard2Controller,
    RegisterController,
    LoginController,
    ProfielController,
    UserCreateController,
    VerlofAanvraagController,
    KeuringController,
    ZiekmeldenController
};

// Redirect root to login
Route::get('/', fn() => redirect()->route('login'));

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // 2FA Routes
    Route::get('/2fa', [LoginController::class, 'show2faForm'])->name('2fa.show');
    Route::post('/2fa', [LoginController::class, 'verify2fa'])->name('2fa.verify');
    Route::post('/2fa/regenerate', [LoginController::class, 'regenerate2fa'])->name('2fa.regenerate');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

<<<<<<< Updated upstream
    Route::get('/dashboard', [KeuringController::class, 'mijnAanvragen'])->name('dashboard');



    Route::get('/profiel', [ProfielController::class, 'show'])->name('profiel.show');
    Route::get('/profiel/bewerken', [ProfielController::class, 'edit'])->name('profiel.edit');
    Route::put('/profiel/update', [ProfielController::class, 'update'])->name('profiel.update');
    Route::put('/profiel/change-password', [ProfielController::class, 'changePassword'])->name('profiel.changePassword');
=======
    // Profiel Routes
    Route::prefix('profiel')->group(function () {
        Route::get('/', [ProfielController::class, 'show'])->name('profiel.show');
        Route::get('/bewerken', [ProfielController::class, 'edit'])->name('profiel.edit');
        Route::put('/update', [ProfielController::class, 'update'])->name('profiel.update');
        Route::put('/change-password', [ProfielController::class, 'changePassword'])->name('profiel.changePassword');
    });
>>>>>>> Stashed changes

    // Ziekmelden Routes
    Route::get('/ziekmelden', [ZiekmeldenController::class, 'index'])->name('ziekmelden.index');
    Route::post('/ziekmelden', [ZiekmeldenController::class, 'store'])->name('ziekmelden.store');

    // User Creation
    Route::get('/addusers', [UserCreateController::class, 'index'])->name('addusers.index');
    Route::post('/addusers', [UserCreateController::class, 'store'])->name('addusers.store');

    // Verlof Aanvragen Routes
    Route::get('/verlofaanvragen', [VerlofAanvraagController::class, 'create'])->name('verlofaanvragen.create');
    Route::post('/verlofaanvragen', [VerlofAanvraagController::class, 'store'])->name('verlofaanvragen.store');

    // Keuring Routes
    Route::get('/keuring', [KeuringController::class, 'index'])->name('keuring.index');
    Route::post('/keuring/update-status/{id}', [KeuringController::class, 'updateStatus'])->name('keuring.updateStatus');
});
