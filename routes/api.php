<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
	Route::middleware('guest')->group(function () {
		Route::post('/register', 'register')->name('register');
		Route::post('/login', 'login')->name('login');
		Route::post('/forgot-password', 'forgotPassword')->name('forgotPassword');
		Route::post('/reset-password', 'resetPassword')->name('resetPassword');
	});

	Route::middleware('auth:sanctum')->group(function () {
		Route::post('/logout', 'logout')->name('logout');
		Route::get('/user', 'getUser')->name('getUser');
	});
});

Route::controller(VerificationController::class)->prefix('email')->name('verification')->group(function () {
	Route::post('/request-verification', 'requestVerification')->name('.requestVerification');
	Route::post('/verify', 'verify')->middleware('throttle:6,1')->name('.verify');
});
