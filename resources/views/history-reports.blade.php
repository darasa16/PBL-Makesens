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

        <div class="bg-white rounded-[30px] p-8 shadow-sm w-full h-auto mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
                <h2 class="text-[32px] font-bold text-black">Sensor Logging</h2>
                
                <div class="flex flex-wrap items-center gap-4 justify-end w-full lg:w-auto">
                    <div class="flex flex-col">
                        <label class="text-sm font-bold mb-1 text-black">Node Location</label>
                        <select class="border-2 border-[#F875AA] rounded-lg px-4 py-2 font-medium text-gray-700 w-64 focus:ring-2 focus:ring-[#F875AA]">
                            <option>Jl. Kalibata Timur IV</option>
                            <option>Situ Babakan</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm font-bold mb-1 text-black">Time Period</label>
                        <input type="date" value="2026-04-30" class="border-2 border-[#F875AA] rounded-lg px-4 py-2 font-medium text-gray-700 focus:ring-2 focus:ring-[#F875AA]">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1200px]">
                    <thead>
                        <tr class="bg-[#AEDEFC]/20 text-black font-bold">
                            <th class="p-4 border-b">No</th>
                            <th class="p-4 border-b">Timestamp</th>
                            <th class="p-4 border-b">Suhu Udara</th>
                            <th class="p-4 border-b">Kelembaban Udara</th>
                            <th class="p-4 border-b">Tekanan Udara</th>
                            <th class="p-4 border-b">Jarak Permukaan Air</th>
                            <th class="p-4 border-b">Laju Aliran Sungai</th>
                            <th class="p-4 border-b">Total Curah Hujan</th>
                            <th class="p-4 border-b">Intensitas Hujan</th>
                            <th class="p-4 border-b">Status Level Air</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 font-medium">
                        @for ($i = 1; $i <= 5; $i++)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="p-4">{{ $i }}</td>
                            <td class="p-4">2026-04-15 10:00</td>
                            <td class="p-4">21.5</td>
                            <td class="p-4">52.7</td>
                            <td class="p-4">1000.3</td>
                            <td class="p-4">1989</td>
                            <td class="p-4">0</td>
                            <td class="p-4">13.41</td>
                            <td class="p-4">0</td>
                            <td class="p-4">0</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-4 gap-2">
                <span class="text-[#F875AA] cursor-pointer">●</span>
                <span class="text-gray-300 cursor-pointer">●</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-[#FFB1B1] rounded-[20px] p-6 flex items-center gap-4 shadow-sm cursor-pointer hover:scale-105 transition-transform">
                <div class="bg-white/40 p-3 rounded-xl">
                    <iconify-icon icon="mdi:file-document-download" class="text-white text-[40px]"></iconify-icon>
                </div>
                <span class="text-white font-bold text-[18px] leading-tight">Download Technical<br>Sensor Log</span>
            </div>
            <div class="bg-[#F875AA] rounded-[20px] p-6 flex items-center gap-4 shadow-sm cursor-pointer hover:scale-105 transition-transform">
                <div class="bg-white/40 p-3 rounded-xl">
                    <iconify-icon icon="ph:file-pdf-fill" class="text-white text-[40px]"></iconify-icon>
                </div>
                <span class="text-white font-bold text-[18px] leading-tight">Download Management<br>Summary</span>
            </div>
            <div class="bg-[#177FB9] rounded-[20px] p-6 flex items-center gap-4 shadow-sm cursor-pointer hover:scale-105 transition-transform">
                <div class="bg-white/40 p-3 rounded-xl">
                    <iconify-icon icon="carbon:report" class="text-white text-[40px]"></iconify-icon>
                </div>
                <span class="text-white font-bold text-[18px] leading-tight">Download Comprehensive<br>Master Report</span>
            </div>
        </div>

    </main>
</div>

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<script>
    // Script Sidebar & Jam (Konsisten 100%)
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

    // ==========================================
    // SINKRONISASI LOGIKA NOTIFIKASI REAL-TIME (KEMBAR DASHBOARD)
    // ==========================================
    const alarmSound = new Audio("{{ asset('audio/sirine.mp3') }}");
    let lastStatusSiaga = 0; 

    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notif-dropdown');
        dropdown.classList.toggle('hidden');
        
        // Sesuai aturan: Hanya mematikan suara sirine saat diklik. Kedipan badge merah jangan hilang.
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

                // ATUR KEDIP BULATAN MERAH (NOTIF-BADGE) & SIRINE AUDIO
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

                // RENDER DAFTAR RIWAYAT NOTIFIKASI 1 MINGGU
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

    // Interval pooling disamakan 1 menit sekali (60000 ms) agar sinkron penuh di semua halaman web
    setInterval(fetchRealtimeNotification, 60000);
    setTimeout(fetchRealtimeNotification, 1000);
</script>
@endsection