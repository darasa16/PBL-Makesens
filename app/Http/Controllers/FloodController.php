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

    // --- ENDPOINT API UNTUK MENGAMBIL DATA FIREBASE SECARA REAL-TIME + FILTER 1 MINGGU ---
    public function getRealtimeData()
    {
        $database = Firebase::database();
        
        // 1. Ambil data 'latest' terkini untuk kotak parameter sensor
        $latestData = $database->getReference('node1/latest')->getValue();
        $latest = !empty($latestData) ? reset($latestData) : null;

        $statusRaw = $latest['prediction_status'] ?? 'Siaga 0';
        $angkaStatus = preg_replace('/[^0-9]/', '', $statusRaw);
        $angkaStatus = !empty($angkaStatus) ? intval($angkaStatus) : 0;

        $floatVal = $latest['float_level'] ?? '0';
        $statusLevel = ($floatVal == '1') ? 'Air Tinggi' : 'Aman';

        // 2. Ambil data 'history' untuk disaring menjadi list riwayat notifikasi 1 minggu
        $historyData = $database->getReference('node1/history')->getValue();
        $historyRaw = !empty($historyData) ? reset($historyData) : [];

        $filteredHistory = [];
        $oneWeekAgo = now()->subDays(7); // Batas dinamis 7 hari ke belakang

        if (is_array($historyRaw)) {
            foreach ($historyRaw as $item) {
                // Ekstraksi status siaga dari item history
                $itemStatusRaw = $item['prediction_status'] ?? $item['status_siaga'] ?? 'Siaga 0';
                $itemAngkaStatus = intval(preg_replace('/[^0-9]/', '', $itemStatusRaw));

                // Ambil string waktu dari data history Firebase (bisa 'timestamp' atau 'datetime' sesuai key database kamu)
                $itemTimeRaw = $item['timestamp'] ?? $item['datetime'] ?? null;
                
                if ($itemTimeRaw) {
                    $itemTime = \Carbon\Carbon::parse($itemTimeRaw);
                    
                    // SELEKSI: Harus dalam range 1 minggu terakhir DAN berstatus Siaga 1 atau Siaga 2
                    if ($itemTime->greaterThanOrEqualTo($oneWeekAgo) && ($itemAngkaStatus === 1 || $itemAngkaStatus === 2)) {
                        $filteredHistory[] = [
                            'siaga' => $itemAngkaStatus,
                            'waktu' => $itemTime->translatedFormat('l, d M Y - H:i') . ' WIB',
                            'pesan' => "Peringatan: Level air masuk kategori kritis Siaga " . $itemAngkaStatus . "!"
                        ];
                    }
                }
            }
        }

        // Balikkan urutan array (reverse) agar data kejadian paling baru ditaruh di atas
        $filteredHistory = array_reverse($filteredHistory);

        return response()->json([
            'suhu' => ($latest['suhu'] ?? '--') . '°C',
            'kelembapan' => ($latest['kelembapan'] ?? '----') . '%',
            'tekanan' => ($latest['tekanan'] ?? '----') . ' hPa',
            'jarak_air' => ($latest['jarak_air'] ?? '----') . ' cm',
            'flow' => ($latest['flow'] ?? '----') . ' L/m',
            'rain_total' => ($latest['rain_total'] ?? '----') . ' mm',
            'rain_rate' => ($latest['rain_rate'] ?? '----') . ' mm/h',
            'status_level' => $statusLevel,
            'angka_status' => $angkaStatus,
            'notif_history' => $filteredHistory // Suplai array riwayat bersih ke JavaScript
        ]);
    }
}