<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public - Login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/app');
});

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return Inertia::render('Auth/Login');
})->name('login');

// Google OAuth
Route::get('/auth/google', [SocialiteController::class , 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class , 'callback']);

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');

    Route::get('/jobs', [JobController::class , 'index'])->name('jobs.index');
    Route::post('/jobs', [JobController::class , 'store'])->name('jobs.store');
    Route::put('/jobs/{job}', [JobController::class , 'update'])->name('jobs.update');
    Route::delete('/jobs/{job}', [JobController::class , 'destroy'])->name('jobs.destroy');

    Route::get('/settings', [ProfileController::class , 'show'])->name('settings');
    Route::put('/settings', [ProfileController::class , 'update'])->name('settings.update');
});