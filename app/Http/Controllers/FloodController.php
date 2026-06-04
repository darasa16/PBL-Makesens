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
        
        $latestData = $database->getReference('node1/latest')->getValue();
        $latest = !empty($latestData) ? reset($latestData) : null;

        $reportsData = $database->getReference('laporan_keluhan')->getValue();
        
        $reports = is_array($reportsData) ? $reportsData : [];
        
        // FIX UTAMA: Tambahkan 'true' agar Key/ID Firebase tidak kere-reset!
        $reports = array_reverse($reports, true); 

        return view('report-management', [
            'latest' => $latest,
            'reports' => $reports 
        ]);
    }

    // --- FUNGSI HALAMAN HISTORY & REPORTS (DEFAULT HARI INI + TAMPIL SEMUA DATA AWAL) ---
    public function history()
    {
        $database = Firebase::database();
        
        // 1. Ambil data mentah 'history' dari path node1/history
        $historyData = $database->getReference('node1/history')->getValue();
        $historyRaw = is_array($historyData) ? $historyData : [];

        // Pembongkar lapisan UID acak Firebase (7QgVPTH...)
        if (count($historyRaw) > 0 && !isset(reset($historyRaw)['suhu'])) {
            $historyRaw = reset($historyRaw); 
        }
        $historyRaw = is_array($historyRaw) ? $historyRaw : [];

        // Balik data terbaru ke atas
        $historyRaw = array_reverse($historyRaw);

        // Set default visual kalender ke tanggal hari ini (WIB)
        $defaultDate = date('Y-m-d'); 

        if (request()->has('date') && !empty(request()->get('date'))) {
            $filterDate = request()->get('date');
            
            // Jalankan penyaringan murni kalau kamu sudah memilih tanggal
            $filteredRaw = [];
            foreach ($historyRaw as $key => $item) {
                if (is_array($item)) {
                    $itemTimestamp = $item['timestamp'] ?? '';
                    if (is_string($itemTimestamp) && str_contains($itemTimestamp, $filterDate)) {
                        $filteredRaw[$key] = $item;
                    }
                }
            }
            $historyRaw = $filteredRaw;
        } else {
            $filterDate = $defaultDate;
        }

        // 2. SELEKSI PAGINATION (100 data per halaman)
        $perPage = 100;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $paginatedItems = array_slice($historyRaw, $offset, $perPage);

        $history = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems, 
            count($historyRaw), 
            $perPage, 
            $currentPage, 
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('history-reports', [
            'historyData' => $history,
            'filterDate' => $filterDate
        ]);
    }

    // --- FUNGSI DOWNLOAD DATA SENSOR MENJADI CSV BERDASARKAN TANGGAL (FIX ANTI-CRASH) ---
    public function downloadSensorCsv()
    {
        $database = Firebase::database();
        
        // 1. Ambil data mentah 'history' dari path node1/history
        $historyData = $database->getReference('node1/history')->getValue();
        $historyRaw = is_array($historyData) ? $historyData : [];

        // Pembongkar lapisan UID acak Firebase
        if (count($historyRaw) > 0 && !isset(reset($historyRaw)['suhu'])) {
            $historyRaw = reset($historyRaw); 
        }
        $historyRaw = is_array($historyRaw) ? $historyRaw : [];

        // Urutkan data terbaru ke atas
        $historyRaw = array_reverse($historyRaw);

        // Cek apakah ada filter tanggal dari parameter link kartu yang diklik
        $filename = 'sensor_log_all.csv';
        if (request()->has('date') && !empty(request()->get('date'))) {
            $filterDate = request()->get('date');
            $filename = 'sensor_log_' . $filterDate . '.csv';
            
            // Lakukan penyaringan data sesuai tanggal
            $filteredRaw = [];
            foreach ($historyRaw as $key => $item) {
                if (is_array($item)) {
                    $itemTimestamp = $item['timestamp'] ?? '';
                    if (is_string($itemTimestamp) && str_contains($itemTimestamp, $filterDate)) {
                        $filteredRaw[$key] = $item;
                    }
                }
            }
            $historyRaw = $filteredRaw;
        }

        // 2. PROSES PEMBUATAN FILE CSV SECARA LANGSUNG (STREAM)
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Struktur 10 kolom CSV yang pas dan sinkron dengan tabel Sensor Logging kamu
        $columns = [
            'No', 
            'Timestamp', 
            'Suhu Udara', 
            'Kelembaban Udara', 
            'Tekanan Udara', 
            'Jarak Permukaan Air', 
            'Laju Aliran Sungai', 
            'Total Curah Hujan', 
            'Intensitas Hujan', 
            'Status Level Air'
        ];

        $callback = function() use($historyRaw, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // Menulis baris header kolom

            $no = 1;
            foreach ($historyRaw as $item) {
                if (!is_array($item)) {
                    continue;
                }

                // PROTEKSI KETAT: Mengubah data kosong / bertipe array menjadi string '-' agar PHP tidak crash
                $timestamp   = isset($item['timestamp']) && !is_array($item['timestamp']) ? $item['timestamp'] : '-';
                $suhu        = isset($item['suhu']) && !is_array($item['suhu']) ? $item['suhu'] : '-';
                $kelembapan  = isset($item['kelembapan']) && !is_array($item['kelembapan']) ? $item['kelembapan'] : '-';
                $tekanan     = isset($item['tekanan']) && !is_array($item['tekanan']) ? $item['tekanan'] : '-';
                $jarak_air   = isset($item['jarak_air']) && !is_array($item['jarak_air']) ? $item['jarak_air'] : '-';
                $flow        = isset($item['flow']) && !is_array($item['flow']) ? $item['flow'] : '-';
                $rain_total  = isset($item['rain_total']) && !is_array($item['rain_total']) ? $item['rain_total'] : '-';
                $rain_rate   = isset($item['rain_rate']) && !is_array($item['rain_rate']) ? $item['rain_rate'] : '-';
                
                $floatLevel  = isset($item['float_level']) && !is_array($item['float_level']) ? $item['float_level'] : '0';
                $status_level = ($floatLevel == '1') ? 'Air Tinggi' : 'Aman';

                fputcsv($file, [
                    $no++,
                    $timestamp,
                    $suhu,
                    $kelembapan,
                    $tekanan,
                    $jarak_air,
                    $flow,
                    $rain_total,
                    $rain_rate,
                    $status_level
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
        $oneWeekAgo = now()->subDays(7); 

        if (is_array($historyRaw)) {
            foreach ($historyRaw as $item) {
                if (is_array($item)) {
                    $itemStatusRaw = $item['prediction_status'] ?? $item['status_siaga'] ?? 'Siaga 0';
                    $itemAngkaStatus = intval(preg_replace('/[^0-9]/', '', $itemStatusRaw));

                    $itemTimeRaw = $item['timestamp'] ?? $item['datetime'] ?? null;
                    
                    if ($itemTimeRaw) {
                        try {
                            $itemTime = \Carbon\Carbon::parse($itemTimeRaw);
                            if ($itemTime->greaterThanOrEqualTo($oneWeekAgo) && ($itemAngkaStatus === 1 || $itemAngkaStatus === 2)) {
                                $filteredHistory[] = [
                                    'siaga' => $itemAngkaStatus,
                                    'waktu' => $itemTime->translatedFormat('l, d M Y - H:i') . ' WIB',
                                    'pesan' => "Peringatan: Level air masuk kategori kritis Siaga " . $itemAngkaStatus . "!"
                                ];
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                }
            }
        }

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
            'notif_history' => $filteredHistory
        ]);
    }

    // 🌟 FUNGSI BARU UNTUK MENYIMPAN PERUBAHAN STATUS LAPORAN KE FIREBASE 🌟
    public function updateReportStatus(Request $request)
    {
        $database = Firebase::database();
        
        // Ambil ID laporan (key) dan status baru dari request web
        $reportId = $request->id;
        $newStatus = $request->status;

        if ($reportId && $newStatus) {
            // Update node status di dalam laporan_keluhan/UID
            $database->getReference('laporan_keluhan/' . $reportId)->update([
                'status' => $newStatus
            ]);

            return response()->json(['success' => true, 'message' => 'Status berhasil disimpan!']);
        }

        return response()->json(['success' => false, 'message' => 'Gagal menyimpan status.'], 400);
    }
}