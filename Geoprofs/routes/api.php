<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;



Route::middleware('auth')->group(function () {
    Route::post('/store-2fa-code', [LoginController::class, 'store2faCodeFromApp']);
    Route::post('/verify-2fa', [LoginController::class, 'verify2fa'])->name('2fa.verify');
    Route::get('/ping', function () {
        return response()->json(['status' => 'OK'], 200);
    });

});


