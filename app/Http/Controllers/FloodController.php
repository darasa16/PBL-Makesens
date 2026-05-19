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
        $latest = !empty($latestData) ? reset($latestData) : null;
        
        // Ambil data dari 'history'
        $historyData = $database->getReference('node1/history')->getValue();
        $historyRaw = !empty($historyData) ? reset($historyData) : [];

        // Ambil 10 data terakhir dari history untuk tabel/grafik mini di dashboard utama
        $history = [];
        if (is_array($historyRaw) && count($historyRaw) > 0) {
            $history = array_slice($historyRaw, -10, 10, true);
        }

        // 2. AMBIL DATA DARI API BMKG
        try {
            $bmkgResponse = Http::get('https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=31.74.08.1002');
            $bmkgData = $bmkgResponse->json();
            $bmkgParams = $bmkgData['data'][0]['cuaca'][0][0] ?? []; 
        } catch (\Exception $e) {
            $bmkgParams = [];
        }

        // 3. KOORDINAT MAPS
        $lat = -6.260932;
        $lng = 106.848414;

        // 4. KONFIGURASI CCTV
        $cctvUrl = "http://34.101.201.35:8888/cam1/index.m3u8";

        return view('dashboard', [
            'latest' => $latest,
            'history' => $history,
            'bmkgParams' => $bmkgParams,
            'lat' => $lat,
            'lng' => $lng,
            'cctvUrl' => $cctvUrl
        ]);
    }

    // --- FUNGSI HALAMAN INTELLIGENT ANALYSIS (BERSIH DARI API ALAMAT) ---
    public function analysis()
    {
        $database = Firebase::database();
        
        // Ambil data 'latest' dari Firebase
        $latestData = $database->getReference('node1/latest')->getValue();
        $latest = !empty($latestData) ? reset($latestData) : null;

        // Ambil data 'history' global
        $historyData = $database->getReference('node1/history')->getValue();
        $historyRaw = !empty($historyData) ? reset($historyData) : [];

        // Ambil maksimal 720 data terakhir
        $history = [];
        if (is_array($historyRaw) && count($historyRaw) > 0) {
            $history = array_slice($historyRaw, -720, 720, true);
        }

        // Ambil data BMKG
        try {
            $bmkgResponse = Http::get('https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=31.74.08.1002');
            $bmkgData = $bmkgResponse->json();
            $bmkgParams = $bmkgData['data'][0]['cuaca'][0][0] ?? []; 
        } catch (\Exception $e) {
            $bmkgParams = [];
        }

        // Koordinat Maps default EWS Pasir Minggu 
        $lat = -6.260932;
        $lng = 106.848414;

        return view('analysis', [
            'latest' => $latest,
            'history' => $history,
            'bmkgParams' => $bmkgParams,
            'lat' => $lat,
            'lng' => $lng
        ]);
    }

    // --- RUTE BARU: FUNGSI HALAMAN REPORT MANAGEMENT ---
    public function reports()
    {
        $database = Firebase::database();
        
        // Ambil data 'latest' dari Firebase (jika nanti butuh data sensor real-time di halaman ini)
        $latestData = $database->getReference('node1/latest')->getValue();
        $latest = !empty($latestData) ? reset($latestData) : null;

        return view('report-management', [
            'latest' => $latest
        ]);
    }

    // --- FUNGSI BARU: HALAMAN HISTORY & REPORTS (DIREK KE VIEW BARU) ---
    public function history()
    {
        return view('history-reports');
    }
}