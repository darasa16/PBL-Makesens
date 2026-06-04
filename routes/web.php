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
    
    // 🌟 RUTE BARU: UPDATE STATUS LAPORAN (POST) 🌟
    Route::post('/update-report-status', [FloodController::class, 'updateReportStatus'])->name('report.updateStatus');
    
    // --- RUTE BARU: HISTORY & REPORTS ---
    Route::get('/history-reports', [FloodController::class, 'history'])->name('history');
    
    // --- PENYESUAIAN BARU: RUTE DOWNLOAD TECHNICAL SENSOR LOG (CSV) ---
    Route::get('/download-sensor-csv', [FloodController::class, 'downloadSensorCsv'])->name('download.sensor.csv');
    
    // --- RUTE PENDUKUNG: API DATA REAL-TIME ---
    Route::get('/api/realtime-flood', [FloodController::class, 'getRealtimeData'])->name('api.realtime');
    
    // Rute Logout
    Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');

});