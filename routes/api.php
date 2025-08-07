<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
        Route::post('/google', 'googleAuth')->name('googleAuth');
        Route::post('/forgot-password', 'forgotPassword')->name('forgotPassword');
        Route::post('/reset-password', 'resetPassword')->name('resetPassword');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });
});

Route::controller(UserController::class)->middleware('auth:sanctum')->prefix('/user')->name('user')->group(function () {
    Route::get('/', 'getUser')->name('.getUser');

    Route::middleware('verified')->group(function () {
        route::post('/update', 'update')->name('.update');
    });
});

Route::controller(VerificationController::class)->prefix('email')->name('verification')->group(function () {
    Route::post('/request-verification', 'requestVerification')->name('.requestVerification');
    Route::post('/verify', 'verify')->middleware('throttle:6,1')->name('.verify');
});

Route::controller(InfoController::class)->group(function () {
    Route::get('/categories', 'getCategories')->name('.categories');
});

Route::controller(MovieController::class)->middleware(['auth:sanctum', 'verified'])->prefix('movies')->name('movies')->group(function () {
    Route::get('/', 'index')->name('.index');
    Route::post('/', 'store')->name('.store');
    Route::get('/{movie}', 'show')->name('.show');
    Route::post('/{movie}', 'update')->name('.update');
    Route::delete('/{movie}', 'destroy')->name('.destroy');
});

Route::controller(QuoteController::class)->middleware(['auth:sanctum', 'verified'])->prefix('quotes')->name('quotes')->group(function () {
    Route::get('/', 'index')->name('.index');
    Route::post('/', 'store')->name('.store');
    Route::post('/{quote}', 'update')->name('.update');
    Route::delete('/{quote}', 'destroy')->name('.destroy');
});
