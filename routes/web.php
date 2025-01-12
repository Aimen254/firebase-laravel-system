<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::resource('users', UserController::class)->middleware('auth');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest'); 
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', [AdminController::class, 'index'])->middleware(['auth'])->name('dashboard');

