<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmploiController;

Route::get('/', [EmploiController::class, 'index'])->name('emplois.index');
Route::get('/emplois', [EmploiController::class, 'index']); // Alias
Route::post('/emplois', [EmploiController::class, 'store'])->name('emplois.store');
Route::get('/api/emplois', [EmploiController::class, 'search'])->name('emplois.search');
Route::get('/emplois/{emploi}', [EmploiController::class, 'show'])->name('emplois.show'); // Assuming show exists or will be needed, though controller didn't have it.
