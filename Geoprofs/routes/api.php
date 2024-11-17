<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorController;


Route::post('/store-2fa-code', [TwoFactorController::class, 'storeCode']);
Route::post('/verify-2fa', [TwoFactorController::class, 'verifyCode'])->name('2fa.verify');
