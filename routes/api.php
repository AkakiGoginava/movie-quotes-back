<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
	Route::middleware('guest')->group(function() {
		Route::post('/register', 'register')->middleware('guest')->name('register');
		Route::post('/login', 'login')->middleware('guest')->name('login');
	});

	Route::middleware('auth:sanctum')->group(function() { 
		Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
		Route::get('/user', 'getUser')->middleware('auth:sanctum')->name('getUser');
	})
});
