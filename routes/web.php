<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\SecurityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/security/logout', [SecurityController::class, 'logout'])->name('logout');

    Route::get('/', [AppController::class, 'homepage'])->name('homepage');
});

Route::middleware('guest')->prefix('security')->name('security.')->controller(SecurityController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'handleLogin');

    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'handleRegister');

    Route::get('/reset-password', 'resetPassword')->name('resetPassword');
});
