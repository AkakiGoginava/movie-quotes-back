<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
	Route::middleware('guest')->group(function () {
		Route::post('/register', 'register')->name('register');
		Route::post('/login', 'login')->name('login');
	});

	Route::middleware('auth:sanctum')->group(function () {
		Route::post('/logout', 'logout')->name('logout');
		Route::get('/user', 'getUser')->name('getUser');
	});
});

Route::controller(VerificationController::class)->prefix('email')->name('verification')->group(function () {
	Route::post('/check-token', 'checkToken')->name('.checkToken');
	Route::post('/request-verification', 'requestVerification')->middleware('throttle:1,120')->name('.requestVerification');
	Route::post('/verify', 'verify')->middleware(['auth:sanctum', 'throttle:6,1'])->name('.verify');
});
