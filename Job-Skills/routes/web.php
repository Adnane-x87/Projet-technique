<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('emplois.index');
});

use App\Http\Controllers\EmploiController;
use App\Http\Controllers\SkillsController;


// Auth Routes (Closures)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('admin');
    }

    return back()->withErrors([
        'email' => 'Identifiants incorrects.',
    ])->onlyInput('email');
});

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
    ]);

    Auth::login($user);
    return redirect('/admin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [EmploiController::class, 'manage'])->name('admin.dashboard');
    
    // Protected Routes (Create, Edit, Delete)
    Route::resource('emplois', EmploiController::class)->except(['index', 'show']);
    Route::resource('skills', SkillsController::class)->only(['index', 'store', 'destroy']);
});

// Public Routes (Placed at bottom to allow specific routes like 'create' to take precedence)
Route::resource('emplois', EmploiController::class)->only(['index', 'show']);

// AJAX API Route
Route::get('/api/emplois', [EmploiController::class, 'search'])->name('api.emplois.search');
