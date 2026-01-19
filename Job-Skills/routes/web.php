<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmploiController;

Route::get('/', [EmploiController::class, 'index'])->name('emplois.index');
Route::get('/emplois', [EmploiController::class, 'index']); 
Route::post('/emplois', [EmploiController::class, 'store'])->name('emplois.store');
Route::get('/api/emplois', [EmploiController::class, 'search'])->name('emplois.search');
Route::get('/emplois/{emploi}', [EmploiController::class, 'show'])->name('emplois.show'); 