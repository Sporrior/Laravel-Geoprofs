<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfielController;
use App\Http\Controllers\VerlofAanvraagController;
use App\Http\Controllers\KeuringController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZiekmeldenController;
use App\Http\Controllers\VerlofDataController;
use App\Http\Controllers\AccounttoevoegenController;
use App\Http\Controllers\CalendarController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/2fa', [LoginController::class, 'show2faForm'])->name('2fa.show');
Route::post('/verify-2fa', [TwoFactorController::class, 'verify2fa']);

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/code-coverage-report', function () {
        return redirect('/code-coverage/index.html');
    });

    Route::get('/profiel', [ProfielController::class, 'show'])->name('profiel.show');
    Route::get('/profiel/bewerken', [ProfielController::class, 'edit'])->name('profiel.edit');
    Route::put('/profiel/update', [ProfielController::class, 'update'])->name('profiel.update');
    Route::put('/profiel/change-password', [ProfielController::class, 'changePassword'])->name('profiel.changePassword');

    Route::get('/ziekmelden', [ZiekmeldenController::class, 'index'])->name('ziekmelden.index');
    Route::post('/ziekmelden', [ZiekmeldenController::class, 'store'])->name('ziekmelden.store');

    Route::get('/account-toevoegen', [AccounttoevoegenController::class, 'index'])->name('account-toevoegen.index');
    Route::post('/account-toevoegen', [AccounttoevoegenController::class, 'store'])->name('account-toevoegen.store');

    Route::get('/verlofaanvragen', [VerlofAanvraagController::class, 'create'])->name('verlofaanvragen.create');
    Route::post('/verlofaanvragen', [VerlofAanvraagController::class, 'store'])->name('verlofaanvragen.store');

    Route::get('/keuring', [KeuringController::class, 'index'])->name('keuring.index');
    Route::post('/keuring/{id}/update', [KeuringController::class, 'updateStatus'])->name('keuring.updateStatus');

    Route::get('/verlofdata', [VerlofDataController::class, 'index'])->name('verlofdata.index');
    Route::get('/verlofdata/export', [VerlofDataController::class, 'export'])->name('verlofdata.export');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

});
