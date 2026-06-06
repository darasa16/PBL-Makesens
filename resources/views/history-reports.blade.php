@extends('layouts.auth') 

@section('title', 'History & Reports - MAKESENSES+AI')

@section('content')
<div class="flex min-h-screen bg-[#AEDEFC] font-['Poppins'] relative">
    
    <aside id="sidebar" class="fixed left-0 top-0 w-[322px] h-full bg-[#FFF6F6] shadow-[0px_4px_4px_10px_#FFDFDF] z-50 transition-all duration-300 overflow-hidden -translate-x-full lg:translate-x-0">
        <div id="sidebar-header" class="p-2 flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-1">
                <img src="{{ asset('images/Logo Makesens Lingkaran.png') }}" class="w-[85px] h-[80px] shrink-0">
                <span class="text-[30px] font-medium text-[#177FB9] sidebar-text whitespace-nowrap">Makesens</span>
            </div>
            <button onclick="toggleSidebar()" class="mr-4 mt-2 hover:scale-110 transition-transform shrink-0">
                <iconify-icon icon="ph:sliders-horizontal-bold" class="text-[#F875AA] text-[24px]"></iconify-icon>
            </button>
        </div>
        
        <nav class="mt-10 px-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-4 text-[#177FB9] font-bold mb-4">
                <iconify-icon icon="mage:dashboard-fill" class="text-[#F875AA] text-[32px] shrink-0"></iconify-icon>
                <span class="text-[20px] sidebar-text whitespace-nowrap">Dashboard</span>
            </a>
            <a href="{{ route('analysis') }}" class="flex items-center gap-3 p-4 text-[#177FB9] font-bold mb-4">
                <iconify-icon icon="fluent:receipt-sparkles-20-filled" class="text-[#F875AA] text-[35px] shrink-0"></iconify-icon>
                <span class="text-[20px] sidebar-text whitespace-nowrap">Intelligent Analysis</span>
            </a>
            <a href="{{ route('reports') }}" class="flex items-center gap-3 p-4 text-[#177FB9] font-bold mb-4">
                <iconify-icon icon="mage:chart-fill" class="text-[#F875AA] text-[32px] shrink-0"></iconify-icon>
                <span class="text-[20px] sidebar-text whitespace-nowrap">Report Management</span>
            </a>
            <a href="{{ route('history') }}" class="flex items-center gap-3 p-4 bg-[#F875AA]/20 rounded-[10px] font-bold mb-4 text-[#177FB9]">
                <iconify-icon icon="mdi:file-clock" class="text-[#F875AA] text-[32px] shrink-0"></iconify-icon>
                <span class="text-[20px] sidebar-text whitespace-nowrap">History & Reports</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="absolute bottom-6 left-0 w-full px-4 pt-4 border-t border-[#FFDFDF]/60">
                @csrf
                <button type="submit" class="flex items-center gap-3 p-4 w-full text-[#F875AA] hover:bg-[#F875AA]/20 rounded-[10px] font-bold transition-all duration-200 focus:outline-none">
                    <iconify-icon icon="streamline-sharp:logout-2-remix" class="text-[32px] shrink-0"></iconify-icon>   
                    <span class="text-[20px] text-[#177FB9] sidebar-text whitespace-nowrap">Logout</span>
                 </button>
            </form>        
        </nav>
    </aside>

    <main id="main-content" class="flex-1 ml-0 lg:ml-[322px] pt-4 px-4 lg:px-10 pb-10 transition-all duration-300 w-full overflow-x-hidden">
        
        <header class="flex flex-col gap-4 sm:flex-row justify-between items-start sm:items-center mb-8 lg:mb-12">
            <button onclick="toggleSidebar()" class="lg:hidden p-2 bg-white rounded-lg shadow-sm flex items-center justify-center">
                <iconify-icon icon="ph:sliders-horizontal-bold" class="text-[#F875AA] text-[24px]"></iconify-icon>
            </button>

            <div class="flex flex-wrap items-center gap-6 lg:gap-10 text-[16px] sm:text-[22px] text-black">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="iconamoon:clock-light" class="text-[#F875AA] text-[24px] sm:text-[28px]"></iconify-icon>
                    <span id="time-display" class="font-medium"></span>
                </div>
                <div class="flex items-center gap-2">
                    <iconify-icon icon="uil:calender" class="text-[#F875AA] text-[24px] sm:text-[28px]"></iconify-icon>
                    <span class="font-medium">{{ now()->format('l, d F Y') }}</span>
                </div>
                
                <div class="relative">
                    <button onclick="toggleNotificationDropdown()" class="relative flex items-center justify-center p-1 hover:scale-110 transition-transform duration-200 focus:outline-none">
                        <iconify-icon icon="tabler:bell" class="text-[#F875AA] text-[26px] sm:text-[32px]"></iconify-icon>
                        <span id="notif-badge" class="absolute top-1 right-1 flex h-2.5 w-2.5 sm:h-3 sm:w-3 hidden">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 sm:h-3 sm:w-3 bg-red-500"></span>
                        </span>
                    </button>

                    <div id="notif-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden text-sm">
                        <div class="bg-[#FFF6F6] p-4 border-b border-[#FFDFDF] flex justify-between items-center">
                            <span class="font-bold text-black">Notifikasi Peringatan</span>
                            <button onclick="clearNotif()" class="text-xs text-[#F875AA] font-semibold hover:underline">Hapus Semua</button>
                        </div>
                        <div id="notif-list" class="max-h-60 overflow-y-auto divide-y divide-gray-50 text-xs">
                            <div id="empty-notif" class="p-4 text-center text-gray-400 italic">Tidak ada notifikasi kritis.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 self-end sm:self-auto">
                <span class="text-[18px] sm:text-[22px] font-medium text-black">
                    Hai, {{ session('firebase_user.nama') ?? session('firebase_user.name') ?? 'User' }}
                </span>    
                <div class="w-[50px] h-[50px] sm:w-[70px] sm:h-[70px] rounded-full shadow-sm overflow-hidden bg-gray-200">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(session('firebase_user.nama') ?? session('firebase_user.name') ?? 'User') }}&background=F875AA&color=fff"
                         alt="Profile" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <div class="bg-white rounded-[30px] p-8 shadow-sm w-full h-fit mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
                <h2 class="text-[32px] font-bold text-black">Log Sensor</h2>
            </div>

            <div class="flex flex-wrap items-center gap-4 mb-8 mt-10 justify-start w-full lg:justify-end">
                <div class="flex flex-col">
                    <label class="text-sm font-bold mb-1 text-black">Node Lokasi</label>
                    <select class="border-2 border-[#F875AA] rounded-lg px-4 py-2 font-medium text-gray-700 w-64 focus:ring-2 focus:ring-[#F875AA]">
                        <option>Jl. Madrasah, Kalibata</option>
                        <option>Other</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-bold mb-1 text-black">Waktu</label>
                    <form id="filter-form" method="GET" action="{{ route('history') }}">
                        <input type="date" name="date" value="{{ $filterDate }}"
                               onchange="document.getElementById('filter-form').submit();"
                               class="border-2 border-[#F875AA] rounded-lg px-4 py-2 font-medium text-gray-700 focus:ring-2 focus:ring-[#F875AA]">
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1600px]">
                    <thead>
                        <tr class="bg-[#AEDEFC]/20 text-black font-bold">
                            <th class="p-4 border-b">No</th>
                            <th class="p-4 border-b">Waktu</th>
                            <th class="p-4 border-b">Suhu Udara</th>
                            <th class="p-4 border-b">Kelembaban Udara</th>
                            <th class="p-4 border-b">Tekanan Udara</th>
                            <th class="p-4 border-b">Jarak Permukaan Air</th>
                            <th class="p-4 border-b">Laju Aliran Sungai</th>
                            <th class="p-4 border-b">Total Curah Hujan</th>
                            <th class="p-4 border-b">Intensitas Hujan</th>
                            <th class="p-4 border-b">Status Level Air</th>
                            <th class="p-4 border-b">CPU</th>
                            <th class="p-4 border-b">Disk Terpakai</th>
                            <th class="p-4 border-b">Rata Rata Load 5 Menit</th>
                            <th class="p-4 border-b">Memori Terpakai</th>
                            <th class="p-4 border-b">RSSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 font-medium">
                        @forelse ($historyData as $index => $log)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4">{{ $log['timestamp'] ?? '-' }}</td>
                            <td class="p-4">{{ $log['suhu'] ?? '-' }} °C</td>
                            <td class="p-4">{{ $log['kelembapan'] ?? '-' }} %</td>
                            <td class="p-4">{{ $log['tekanan'] ?? '-' }} hPa</td>
                            <td class="p-4">{{ $log['jarak_air'] ?? '-' }} cm</td>
                            <td class="p-4">{{ $log['flow'] ?? '-' }} L/m</td>
                            <td class="p-4">{{ $log['rain_total'] ?? '-' }} mm</td>
                            <td class="p-4">{{ $log['rain_rate'] ?? '-' }} mm/h</td>
                            <td class="p-4">
                                @if(($log['float_level'] ?? '0') == '1')
                                    Air Tinggi
                                @else
                                    Aman
                                @endif
                            </td>
                            <td class="p-4">{{ $log['cpu_percent'] ?? '-' }} %</td>
                            <td class="p-4">{{ $log['disk_used_gb'] ?? '-' }} GB</td>
                            <td class="p-4">{{ $log['load_avg_5min'] ?? '-' }}</td>
                            <td class="p-4">{{ $log['memory_used_mb'] ?? '-' }} MB</td>
                            <td class="p-4">{{ $log['rssi'] ?? '-' }} dBm</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="15" class="p-8 text-center text-gray-400 italic">
                                Belum ada riwayat data sensor log dari Sensor Node.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="flex justify-end mt-6 gap-4 items-center">
                {{-- Tombol Panah Kiri --}}
                @if ($historyData->onFirstPage())
                    <span class="text-gray-300 cursor-not-allowed flex items-center justify-center p-2 rounded-lg bg-gray-100">
                        <iconify-icon icon="mingcute:arrow-left-line" class="text-[24px]"></iconify-icon>
                    </span>
                @else
                    <a href="{{ $historyData->previousPageUrl() }}" class="text-[#F875AA] hover:bg-[#F875AA]/10 flex items-center justify-center p-2 rounded-lg bg-[#FFF6F6] border border-[#FFDFDF] transition-colors">
                        <iconify-icon icon="mingcute:arrow-left-line" class="text-[24px]"></iconify-icon>
                    </a>
                @endif

                {{-- Indikator Teks Halaman --}}
                <span class="text-sm font-bold text-gray-600 font-['Poppins']">
                    Halaman {{ $historyData->currentPage() }} dari {{ $historyData->lastPage() }}
                </span>

                {{-- Tombol Panah Kanan --}}
                @if ($historyData->hasMorePages())
                    <a href="{{ $historyData->nextPageUrl() }}" class="text-[#F875AA] hover:bg-[#F875AA]/10 flex items-center justify-center p-2 rounded-lg bg-[#FFF6F6] border border-[#FFDFDF] transition-colors">
                        <iconify-icon icon="mingcute:arrow-right-line" class="text-[24px]"></iconify-icon>
                    </a>
                @else
                    <span class="text-gray-300 cursor-not-allowed flex items-center justify-center p-2 rounded-lg bg-gray-100">
                        <iconify-icon icon="mingcute:arrow-right-line" class="text-[24px]"></iconify-icon>
                    </span>
                @endif
            </div>
        </div> 
        
       <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a id="card-download-sensor" href="{{ route('download.sensor.csv', ['date' => request()->get('date')]) }}" class="bg-[#FFFFFF] rounded-[20px] p-6 flex items-center gap-4 shadow-sm cursor-pointer hover:scale-105 transition-transform no-underline text-black">
                <div class="bg-white/40 p-3 rounded-xl">
                    <iconify-icon icon="flowbite:file-csv-solid" style="color: #F875AA;" class="text-[60px]"></iconify-icon>
                </div>
                <span class="text-black font-medium text-[18px] leading-tight">Download Sensor Log</span>
            </a>
            
            <a id="card-download-pdf" href="{{ route('download.report.pdf', ['date' => request()->get('date')]) }}" class="bg-[#FFFFFF] rounded-[20px] p-6 flex items-center gap-4 shadow-sm cursor-pointer hover:scale-105 transition-transform no-underline text-black">
                <div class="bg-white/40 p-3 rounded-xl">
                    <iconify-icon icon="mingcute:pdf-fill" style="color: #F875AA;" class="text-[60px]"></iconify-icon>
                </div>
                <span class="text-black font-medium text-[18px] leading-tight">Download Pengelolaan<br>Laporan</span>
            </a>
            
            <a id="card-download-combined" href="{{ route('download.combined.csv', ['date' => request()->get('date')]) }}" class="bg-[#FFFFFF] rounded-[20px] p-6 flex items-center gap-4 shadow-sm cursor-pointer hover:scale-105 transition-transform no-underline text-black">
                <div class="bg-white/40 p-3 rounded-xl">
                    <iconify-icon icon="flowbite:file-csv-solid" style="color: #F875AA;" class="text-[60px]"></iconify-icon>
                </div>
                <span class="text-black font-medium text-[18px] leading-tight">Download Laporan<br>Sensor & Keluhan</span>
            </a>
        </div>

    </main>
</div>

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const sidebarHeader = document.getElementById('sidebar-header');
        const texts = document.querySelectorAll('.sidebar-text');
        const isMobile = window.innerWidth < 1024;

        if (isMobile) {
            sidebar.classList.toggle('-translate-x-full');
        } else {
            if (sidebar.classList.contains('w-[322px]')) {
                sidebar.classList.replace('w-[322px]', 'w-[105px]');
                sidebarHeader.classList.replace('justify-between', 'justify-center');
                texts.forEach(t => t.classList.add('hidden'));
                mainContent.classList.replace('lg:ml-[322px]', 'lg:ml-[105px]');
            } else {
                sidebar.classList.replace('w-[105px]', 'w-[322px]');
                sidebarHeader.classList.replace('justify-center', 'justify-between');
                texts.forEach(t => t.classList.remove('hidden'));
                mainContent.classList.replace('lg:ml-[105px]', 'lg:ml-[322px]');
            }
        }
    }

    function updateClock() {
        const now = new Date();
        const display = document.getElementById('time-display');
        if(display) {
            display.innerText = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true 
            });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    const alarmSound = new Audio("{{ asset('audio/sirine.mp3') }}");
    let lastStatusSiaga = 0; 

    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notif-dropdown');
        dropdown.classList.toggle('hidden');
        
        if (!dropdown.classList.contains('hidden')) {
            alarmSound.pause();
            alarmSound.currentTime = 0;
        }
    }

    function clearNotif() {
        document.getElementById('notif-list').innerHTML = '<div id="empty-notif" class="p-4 text-center text-gray-400 italic">Tidak ada notifikasi kritis.</div>';
    }

    function fetchRealtimeNotification() {
        fetch("{{ route('api.realtime') }}")
            .then(response => response.json())
            .then(data => {
                const currentSiaga = data.angka_status;

                const notifBadge = document.getElementById('notif-badge');
                if (currentSiaga === 1 || currentSiaga === 2) {
                    notifBadge.classList.remove('hidden');
                    
                    if (lastStatusSiaga !== 1 && lastStatusSiaga !== 2) {
                        alarmSound.loop = true;
                        alarmSound.play().catch(error => console.log("Audio play blocked by browser setup:", error));
                    }
                } else if (currentSiaga === 3 || currentSiaga === 0) {
                    notifBadge.classList.add('hidden');
                    alarmSound.pause();
                    alarmSound.currentTime = 0;
                }

                const notifList = document.getElementById('notif-list');
                if (data.notif_history && data.notif_history.length > 0) {
                    let htmlContent = '';
                    data.notif_history.forEach(item => {
                        let badgeColor = item.siaga === 1 ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700';
                        htmlContent += `
                            <div class="p-4 hover:bg-gray-50 transition-colors flex flex-col gap-1 font-['Poppins']">
                                <div class="flex justify-between items-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${badgeColor}">SIAGA ${item.siaga}</span>
                                    <span class="text-[10px] text-gray-400">${item.waktu}</span>
                                </div>
                                <p class="text-gray-700 font-medium">${item.pesan}</p>
                            </div>
                        `;
                    });
                    notifList.innerHTML = htmlContent;
                } else {
                    notifList.innerHTML = '<div id="empty-notif" class="p-4 text-center text-gray-400 italic">Tidak ada riwayat siaga 1 & 2 dalam 1 minggu terakhir.</div>';
                }

                lastStatusSiaga = currentSiaga;
            })
            .catch(error => console.error("Gagal sinkronisasi data riwayat API di halaman History Reports:", error));
    }

    setInterval(fetchRealtimeNotification, 60000);
    setTimeout(fetchRealtimeNotification, 1000);
</script>
@endsection