@extends('layouts.auth') 

@section('title', 'Intelligent Analysis - MAKESENSES+AI')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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
            <a href="{{ route('analysis') }}" class="flex items-center gap-3 p-4 bg-[#F875AA]/20 rounded-[10px] font-bold mb-4">
                <iconify-icon icon="fluent:receipt-sparkles-20-filled" class="text-[#F875AA] text-[35px] shrink-0"></iconify-icon>
                <span class="text-[20px] text-[#177FB9] sidebar-text whitespace-nowrap">Intelligent Analysis</span>
            </a>
            <a href="{{ route('reports') }}" class="flex items-center gap-3 p-4 text-[#177FB9] font-bold mb-4">    
                <iconify-icon icon="mage:chart-fill" class="text-[#F875AA] text-[32px] shrink-0"></iconify-icon>
                <span class="text-[20px] sidebar-text whitespace-nowrap">Report Management</span>
            </a>
            <a href="{{ route('history') }}" class="flex items-center gap-3 p-4 text-[#177FB9] font-bold mb-4">    
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
                
                <button class="relative flex items-center justify-center p-1 hover:scale-110 transition-transform duration-200 focus:outline-none">
                    <iconify-icon icon="tabler:bell" class="text-[#F875AA] text-[26px] sm:text-[32px]"></iconify-icon>
                    <span class="absolute top-1 right-1 flex h-2.5 w-2.5 sm:h-3 sm:w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 sm:h-3 sm:w-3 bg-red-500"></span>
                    </span>
                </button>
            </div>

            <div class="flex items-center gap-4 self-end sm:self-auto">
                <span class="text-[18px] sm:text-[22px] font-medium text-black">
                    Hai, {{ session('firebase_user.nama') ?? session('firebase_user.name') ?? 'User' }}
                </span>    

                <div class="w-[50px] h-[50px] sm:w-[70px] sm:h-[70px] rounded-full shadow-sm overflow-hidden bg-gray-200">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(session('firebase_user.nama') ?? session('firebase_user.name') ?? 'User') }}&background=F875AA&color=fff"
                         alt="Profile"
                         class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-[40px] mb-[40px] w-full items-stretch">
            
            <div class="lg:col-span-2 flex flex-col gap-[40px]">
                <div class="bg-white rounded-[30px] p-8 shadow-sm flex flex-col min-h-[480px] w-full">
                    <div class="flex items-center gap-2 mb-3">
                        <h4 class="text-[22px] font-bold text-black -mt-[3px]">Ketinggian Air</h4>
                    </div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-3 h-3 bg-[#177FB9] rounded-full"></span>
                        <p class="text-gray-500 font-medium">Sensor Water Level</p>
                    </div>
                    <div class="flex-1 w-full relative min-h-[300px]">
                        <canvas id="waterLevelChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-[30px] p-8 shadow-sm flex flex-row items-center min-h-[160px] h-full w-full gap-4">
                    <div class="flex flex-col flex-1">
                        <h4 class="text-[22px] font-bold text-black -mt-[15px] mb-4">Diagnosis Penyebab Banjir</h4> 
                        <p class="text-[17px] text-gray-600 leading-relaxed">
                            Penyebab: {{ !empty($latest['diagnosis']) ? $latest['diagnosis'] : 'Tidak terjadi banjir' }}
                        </p>
                    </div>
                    <iconify-icon icon="material-symbols:flood-rounded" class="text-[#F875AA] text-[100px] shrink-0 ml-auto"></iconify-icon>
                </div>
            </div>    

            <div class="lg:col-span-1 flex flex-col gap-[40px]">
                <div class="bg-white rounded-[30px] p-6 shadow-sm flex flex-col justify-between flex-1 min-h-[480px]">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <iconify-icon icon="fluent-mdl2:map-pin-12" class="text-[32px] shrink-0" style="color: #F875AA;"></iconify-icon>
                            <h4 class="text-[22px] font-bold text-black">Luas Daerah Terdampak</h4>
                        </div>
                        <p class="font-bold text-[16px] mb-3 text-black">
                            Total Wilayah: <span>{{ $latest['affected_area_ha'] ?? '0' }} ha</span>
                        </p>
                        
                        <div class="relative rounded-xl overflow-hidden mb-4 border-2 border-gray-100">
                            <div id="map" class="w-full h-[200px] z-10"></div>
                        </div>

                        <h5 class="font-bold text-[16px] mb-1">Wilayah yang Terdampak</h5>
                        <p class="text-[#177FB9] font-bold text-[15px] mb-2">
                            {{ $latest['affected_kk'] ?? '0' }} Kartu Keluarga
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-[30px] p-6 shadow-sm flex flex-col justify-center min-h-[160px] h-full w-full">
                    <div class="flex items-center gap-2 mb-2">
                        <h4 class="text-[22px] font-bold text-black mb-2 -mt-[10px]">Probabilitas Banjir</h4>
                    </div>
                    <p class="text-[40px] font-bold text-[#177FB9] leading-none">
                        {{ $latest['probability'] ?? '0' }} %
                    </p>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[40px] w-full items-stretch">
            
            <div class="bg-white rounded-[30px] p-6 shadow-sm flex flex-col justify-center min-h-[160px]">
                <h5 class="text-[22px] font-bold text-black mb-12">Estimasi Anggaran Bantuan</h5>

                <div class="block -mt-[8px]">
                    <span class="inline-block bg-[#AEDEFC]/45 text-[36px] font-bold text-[#000000] px-4 py-1 rounded-[15px]">
                        Rp {{ isset($latest['total_budget_idr']) ? number_format($latest['total_budget_idr'], 0, ',', '.') : '0' }}
                    </span>
                </div>        
            </div>

            <div class="bg-white rounded-[30px] p-6 shadow-sm flex flex-col min-h-[160px]">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <iconify-icon icon="mdi:shield-check" class="text-[#F875AA] text-[26px]"></iconify-icon>
                        <h5 class="text-[22px] font-bold text-black">Pencegahan Agar Tidak Banjir</h5>
                    </div>
                    
                    <div class="max-h-[80px] overflow-y-auto pr-1">
                        <ul class="list-disc list-inside text-gray-700 space-y-1 text-[16px] font-medium pl-1">
                            <li>Rajin Bersihkan Got dan Saluran Air</li>
                            <li>Buat Lubang Resapan Air</li>
                            <li> Buang Sampah pada Tempatnya</li>
                            <li>Menanam Pohon atau Tanaman</li>
                            <li>Siapkan Tas Siaga Bencana</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-auto text-right pt-2">
                    <span class="text-[12px] font-medium text-gray-400 tracking-wide italic block">
                        Sumber dari https://sda.pu.go.id/
                    </span>
                </div>
            </div>

        </div>

    </main>
</div>

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Logic Sidebar Collapse
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const sidebarHeader = document.getElementById('sidebar-header');
        const texts = document.querySelectorAll('.sidebar-text');

        const isMobile = window.innerWidth < 1024;

        if (isMobile) {
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        } else {
            if (sidebar.classList.contains('w-[322px]')) {
                sidebar.classList.replace('w-[322px]', 'w-[105px]');
                sidebarHeader.classList.replace('justify-between', 'justify-center');
                texts.forEach(t => t.classList.add('hidden'));
                mainContent.classList.remove('lg:ml-[322px]');
                mainContent.classList.add('lg:ml-[105px]');
            } else {
                sidebar.classList.replace('w-[105px]', 'w-[322px]');
                sidebarHeader.classList.replace('justify-center', 'justify-between');
                texts.forEach(t => t.classList.remove('hidden'));
                mainContent.classList.remove('lg:ml-[105px]');
                mainContent.classList.add('lg:ml-[322px]');
            }
        }
    }

    // Jam Digital Real-time
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

    // --- SINKRONISASI DATA GRAFIK STRUKTUR FIXED 24 JAM (ASLI MILIK KAMU - OVERWRITE MODE) ---
    const fixedLabels = [
        '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', 
        '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '23:59'
    ];

    let chartWaterLevels = new Array(fixedLabels.length).fill(null);

    const todayDateStr = "{{ now()->format('Y-m-d') }}";
    const historyData = @json($history ?? []);

    if (historyData && Object.keys(historyData).length > 0) {
        Object.values(historyData).forEach(item => {
            if (item.timestamp && item.timestamp.length >= 10) {
                let itemDate = item.timestamp.substring(0, 10);

                if (itemDate === todayDateStr) {
                    let hourStr = item.timestamp.substring(11, 13); 
                    let hourIndex = parseInt(hourStr, 10);

                    if (hourStr === "23") {
                        chartWaterLevels[24] = item.jarak_air ?? 0;
                    } else if (hourIndex >= 0 && hourIndex < 23) {
                        chartWaterLevels[hourIndex] = item.jarak_air ?? 0;
                    }
                }
            }
        });
    }

    const latestData = @json($latest ?? []);
    if (latestData && latestData.timestamp && latestData.timestamp.length >= 10) {
        let latestDate = latestData.timestamp.substring(0, 10);

        if (latestDate === todayDateStr) {
            let latestHourStr = latestData.timestamp.substring(11, 13);
            let latestHourIndex = parseInt(latestHourStr, 10);
            
            if (latestHourStr === "23") {
                if (chartWaterLevels[24] === null) chartWaterLevels[24] = latestData.jarak_air ?? 0;
            } else if (latestHourIndex >= 0 && latestHourIndex < 23) {
                if (chartWaterLevels[latestHourIndex] === null) chartWaterLevels[latestHourIndex] = latestData.jarak_air ?? 0;
            }
        }
    }

    // --- LOGIKA SKRIP PETA RADIUS LUASAN BANJIR (100% AMAN DAN PRESISI) ---
    const centerLat = {{ $lat }};
    const centerLng = {{ $lng }};
    const affectedAreaHa = parseFloat("{{ $latest['affected_area_ha'] ?? 0 }}");

    const map = L.map('map').setView([centerLat, centerLng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([centerLat, centerLng]).addTo(map)
        .bindPopup("<b>Node 1</b><br>Lokasi Utama Sensor Air.")
        .openPopup();

    if (affectedAreaHa > 0) {
        const radiusInMeters = Math.sqrt((affectedAreaHa * 10000) / Math.PI);

        L.circle([centerLat, centerLng], {
            color: '#F875AA',
            fillColor: '#F875AA',
            fillOpacity: 0.35,
            radius: radiusInMeters
        }).addTo(map).bindPopup(`Estimasi Luas Genangan: ${affectedAreaHa} ha`);
        
        const circleBounds = L.circle([centerLat, centerLng], { radius: radiusInMeters }).getBounds();
        map.fitBounds(circleBounds);
    }

    // RENDER GRAFIK WATER LEVEL CHART.JS (100% UTUH ASLI KAMU)
    const ctx = document.getElementById('waterLevelChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: fixedLabels, 
            datasets: [{
                label: 'Ketinggian Air (cm)',
                data: chartWaterLevels, 
                borderColor: '#177FB9',
                backgroundColor: 'rgba(23, 127, 185, 0.1)',
                fill: true,
                spanGaps: true, 
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 5, 
                pointBackgroundColor: '#177FB9'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: 500, 
                    ticks: {
                        stepSize: 100,
                        font: { family: 'Poppins', size: 11 }
                    },
                    grid: { color: '#e5e7eb' },
                    title: {
                        display: true,
                        text: 'Water Level (cm)',
                        color: '#000000',
                        font: { family: 'Poppins', size: 13, weight: 'normal' },
                        padding: { bottom: 10 }
                    }
                },
                x: {
                    grid: { color: '#e5e7eb' },
                    ticks: {
                        font: { family: 'Poppins', size: 11 },
                        maxRotation: 0,
                        autoSkip: false, 
                        callback: function(val, index) {
                            let label = this.getLabelForValue(val);
                            let targetHours = ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00', '23:59'];
                            return targetHours.includes(label) ? label : '';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Waktu (Real-time)',
                        color: '#000000',
                        font: { family: 'Poppins', size: 13, weight: 'normal' },
                        padding: { top: 8 }
                    }
                }
            }
        }
    });

    lucide.createIcons();
</script>
@endsection