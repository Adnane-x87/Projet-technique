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
    Route::get('/admin', [EmploiController::class, 'manage'])->name('admin.dashboard');
    
    Route::get('/emplois/create', [EmploiController::class, 'create'])->name('emplois.create');
    Route::post('/emplois', [EmploiController::class, 'store'])->name('emplois.store');
    Route::get('/emplois/{emploi}/edit', [EmploiController::class, 'edit'])->name('emplois.edit');
    Route::put('/emplois/{emploi}', [EmploiController::class, 'update'])->name('emplois.update');
    Route::delete('/emplois/{emploi}', [EmploiController::class, 'destroy'])->name('emplois.destroy'); // Fixed route for DELETE

    Route::resource('skills', SkillsController::class)->only(['index', 'store', 'destroy']);
});

// AJAX API Route
Route::get('/api/emplois', [EmploiController::class, 'search'])->name('api.emplois.search');
