<?php

use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\FloodController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - MAKESENSES+AI
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// --- RUTE LOGIN FIREBASE ---
Route::get('/login', [FirebaseAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [FirebaseAuthController::class, 'login']);

// --- RUTE TERPROTEKSI (HARUS LOGIN) ---
Route::middleware(['checkFirebase'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [FloodController::class, 'index'])->name('dashboard');

    // Intelligent Analysis
    Route::get('/analysis', [FloodController::class, 'analysis'])->name('analysis');
    
    // Report Management
    Route::get('/report-management', [FloodController::class, 'reports'])->name('reports');
    
    // --- RUTE BARU: HISTORY & REPORTS ---
    Route::get('/history-reports', [FloodController::class, 'history'])->name('history');
    
    // Rute Logout
    Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');
});