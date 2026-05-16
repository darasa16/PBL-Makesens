<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Kreait\Laravel\Firebase\Facades\Firebase;

class FloodController extends Controller
{
    public function index()
    {
        // 1. KONEKSI & AMBIL DATA FIREBASE
        $database = Firebase::database();
        
        // Ambil data 'latest' dari Firebase
        $latestData = $database->getReference('node1/latest')->getValue();

        // Trik reset() untuk mengambil data di dalam ID unik (7QgVPT...)
        $latest = !empty($latestData) ? reset($latestData) : null;
        
        // Ambil 10 data terakhir dari 'history' untuk tabel atau grafik
        $history = $database->getReference('node1/history')
                            ->orderByKey()
                            ->limitToLast(10)
                            ->getValue();

        // 2. AMBIL DATA DARI API BMKG
        try {
            $bmkgResponse = Http::get('https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=31.74.08.1002');
            $bmkgData = $bmkgResponse->json();
            
            // Mengambil parameter cuaca pertama dari data BMKG
            $bmkgParams = $bmkgData['data'][0]['cuaca'][0][0] ?? []; 
        } catch (\Exception $e) {
            $bmkgParams = [];
        }

        // 3. KOORDINAT MAPS KABUPATEN BEKASI
        $lat = -6.260403923230099;
        $lng = 106.84755218214767;

        // 4. KONFIGURASI CCTV
        $cctvUrl = "http://34.101.201.35:8888/cam1/index.m3u8";

        // Kirim semua data ke view dashboard menggunakan array
        return view('dashboard', [
            'latest' => $latest,
            'history' => $history,
            'bmkgParams' => $bmkgParams,
            'lat' => $lat,
            'lng' => $lng,
            'cctvUrl' => $cctvUrl
        ]);
    }

    // --- FUNGSI BARU UNTUK HALAMAN INTELLIGENT ANALYSIS ---
    public function analysis()
    {
        $database = Firebase::database();
        
        // Tetap ambil data 'latest' agar jika sensor mati, grafik/kondisi air tahu keadaan real-time
        $latestData = $database->getReference('node1/latest')->getValue();
        $latest = !empty($latestData) ? reset($latestData) : null;

        // Ambil history data sensor untuk menyuplai grafik Chart.js di halaman analysis
        $history = $database->getReference('node1/history')
                            ->orderByKey()
                            ->limitToLast(10)
                            ->getValue();

        // Ambil data BMKG supaya komponen cuaca di header & info cuaca tetap singkron
        try {
            $bmkgResponse = Http::get('https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=31.74.08.1002');
            $bmkgData = $bmkgResponse->json();
            $bmkgParams = $bmkgData['data'][0]['cuaca'][0][0] ?? []; 
        } catch (\Exception $e) {
            $bmkgParams = [];
        }

        // Koordinat Maps default EWS Pasir Minggu Kabupaten Bekasi
        $lat = -6.260403923230099;
        $lng = 106.84755218214767;

        // Return ke view analysis.blade.php yang baru saja kamu buat
        return view('analysis', [
            'latest' => $latest,
            'history' => $history,
            'bmkgParams' => $bmkgParams,
            'lat' => $lat,
            'lng' => $lng
        ]);
    }
}