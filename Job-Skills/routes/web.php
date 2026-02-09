<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmploiController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Public Routes
Route::get('/', function () {
    return redirect()->route('emplois.index');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/admin', [EmploiController::class, 'manage'])
        ->middleware('can:access-admin')
        ->name('admin.dashboard');

    // CRUD Routes
    Route::resource('emplois', EmploiController::class)->except(['index', 'show']);
    Route::resource('skills', SkillsController::class)->only(['index', 'store', 'destroy']);
});

// Public Read-Only Routes
Route::get('/emplois', [EmploiController::class, 'index'])->name('emplois.index');
Route::get('/emplois/{emploi}', [EmploiController::class, 'show'])->name('emplois.show');

// AJAX API Route
Route::get('/api/emplois', [EmploiController::class, 'search'])->name('api.emplois.search');
