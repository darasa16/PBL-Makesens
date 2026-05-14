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
// Menampilkan halaman login desain Figma kamu
Route::get('/login', [FirebaseAuthController::class, 'showLogin'])->name('login');
// Memproses verifikasi Email & Password ke Firebase
Route::post('/login', [FirebaseAuthController::class, 'login']);

// --- RUTE TERPROTEKSI (HARUS LOGIN) ---
// Semua rute di dalam grup ini akan dicek oleh satpam 'checkFirebase'
Route::middleware(['checkFirebase'])->group(function () {
    
    // Dashboard Utama: Mengambil data sensor dari FloodController
    Route::get('/dashboard', [FloodController::class, 'index'])->name('dashboard');
    
    // Rute Logout
    Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');
});

// PENTING: Jangan tambahkan require __DIR__.'/auth.php' lagi 
// agar Laravel tidak memanggil rute MySQL bawaan Breeze.