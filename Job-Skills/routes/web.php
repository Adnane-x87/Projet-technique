<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmploiController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Public Routes
Route::get('/', function () {
    return redirect()->route('emplois.index');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

Route::get('/emplois', [EmploiController::class, 'index'])->name('emplois.index');
Route::get('/emplois/{emploi}', [EmploiController::class, 'show'])->name('emplois.show');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // User specific protected routes can go here if any remain (e.g. applying to jobs)
});

// AJAX API Route
Route::get('/api/emplois', [EmploiController::class, 'search'])->name('api.emplois.search');
