<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmploiController;
use App\Http\Controllers\SkillsController;

// Redirect home to jobs list
Route::get('/', function () {
    return redirect()->route('emplois.index');
});

// Admin Dashboard (now public)
Route::get('/admin', [EmploiController::class, 'manage'])->name('admin.dashboard');

// All CRUD Routes (now public)
Route::resource('emplois', EmploiController::class);
Route::resource('skills', SkillsController::class)->only(['index', 'store', 'destroy']);

// AJAX API Route
Route::get('/api/emplois', [EmploiController::class, 'search'])->name('api.emplois.search');
