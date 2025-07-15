<?php

use App\Http\Controllers\AuthController;
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
