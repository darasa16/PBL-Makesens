@extends('layouts.auth') 

@section('title', 'Dashboard MAKESENSES+AI')

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
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-4 bg-[#F875AA]/20 rounded-[10px] font-bold mb-4">
                <iconify-icon icon="mage:dashboard-fill" class="text-[#F875AA] text-[32px] shrink-0"></iconify-icon>
                <span class="text-[20px] text-[#177FB9] sidebar-text whitespace-nowrap">Dashboard</span>
            </a>
            <a href="{{ route('analysis') }}" class="flex items-center gap-3 p-4 text-[#177FB9] font-bold mb-4">
                <iconify-icon icon="fluent:receipt-sparkles-20-filled" class="text-[#F875AA] text-[35px] shrink-0"></iconify-icon>
                <span class="text-[20px] sidebar-text whitespace-nowrap">Intelligent Analysis</span>
            </a>
            <a href="#" class="flex items-center gap-3 p-4 text-[#177FB9] font-bold mb-4">
                <iconify-icon icon="mage:chart-fill" class="text-[#F875AA] text-[32px] shrink-0"></iconify-icon>
                <span class="text-[20px] sidebar-text whitespace-nowrap">Report Management</span>
            </a>
            <a href="#" class="flex items-center gap-3 p-4 text-[#177FB9] font-bold mb-4">
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

        <div class="flex flex-wrap gap-5 lg:gap-[30px] mb-12 w-full">
            @php
                $floatVal = $latest['float_level'] ?? '0';
                $statusLevel = ($floatVal == '1') ? 'Air Tinggi' : 'Aman';

                $sensors = [
                    ['Suhu Udara', ($latest['suhu'] ?? '--') . '°C'],
                    ['Kelembaban Udara', ($latest['kelembapan'] ?? '----') . '%'],
                    ['Tekanan Udara', ($latest['tekanan'] ?? '----') . ' hPa'],
                    ['Jarak Air', ($latest['jarak_air'] ?? '----') . ' cm'],
                    ['Laju Aliran', ($latest['flow'] ?? '----') . ' L/m'],
                    ['Total Hujan', ($latest['rain_total'] ?? '----') . ' mm'],
                    ['Intensitas Hujan', ($latest['rain_rate'] ?? '----') . ' mm/h'],
                    ['Status Level', $statusLevel],
                ];
            @endphp

            @foreach($sensors as $sensor)
            <div class="bg-white rounded-[15px] p-4 h-[106px] flex-1 min-w-[calc(50%-20px)] sm:min-w-[calc(50%-20px)] lg:min-w-[calc(25%-30px)] flex flex-col justify-center text-center shadow-sm transition-all duration-300 hover:shadow-md">
                <h3 class="text-[18px] font-bold text-black">{{ $sensor[0] }}</h3>
                <p class="text-[18px] font-bold mt-2 {{ $sensor[1] == 'Air Tinggi' ? 'text-red-600 animate-pulse' : 'text-black' }}">{{ $sensor[1] }}</p>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[40px] mb-12 w-full items-stretch">
            
            <div class="flex flex-col gap-[40px] w-full h-full justify-between transition-all duration-300">
                <div class="bg-[#177FB9] rounded-[30px] p-6 h-[350px] sm:h-[500px] flex flex-col shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <iconify-icon icon="mdi:camera" class="text-white text-[28px]"></iconify-icon>
                        <h4 class="text-white text-[25px] font-bold">CCTV</h4>
                    </div>
                    <div class="flex-1 bg-black rounded-[10px] border-[8px] border-[#177FB9] overflow-hidden">
                        <video id="video-cctv" class="w-full h-full object-cover" controls autoplay muted></video>
                    </div>
                </div>

                <div class="bg-white rounded-[20px] pt-4 px-8 pb-5 min-h-[140px] h-auto flex flex-col w-full shadow-sm relative overflow-hidden">
                    <h4 class="text-black text-[20px] font-bold mb-4 mt-[10px]">Status Cuaca by BMKG</h4>
                    <div class="flex flex-wrap items-center gap-6 sm:gap-10">
                        @php
                            $weather = strtolower($bmkgParams['weather_desc'] ?? '');
                            $hour = now()->format('H'); 
                            $isNight = ($hour >= 18 || $hour <= 05); 
                        @endphp

                        @if(str_contains($weather, 'hujan'))
                            <iconify-icon icon="wi:rain" class="text-[#F875AA] text-[80px] sm:text-[100px]"></iconify-icon>
                        @elseif(str_contains($weather, 'petir'))
                            <iconify-icon icon="wi:thunderstorm" class="text-[#F875AA] text-[80px] sm:text-[100px]"></iconify-icon>
                        @elseif(str_contains($weather, 'kabur') || str_contains($weather, 'kabut'))
                            <iconify-icon icon="wi:fog" class="text-[#F875AA] text-[80px] sm:text-[100px]"></iconify-icon>
                        @elseif(str_contains($weather, 'berawan') || str_contains($weather, 'mendung'))
                            <iconify-icon icon="{{ $isNight ? 'wi:night-alt-cloudy' : 'wi:day-cloudy' }}" class="text-[#F875AA] text-[80px] sm:text-[100px]"></iconify-icon>
                        @elseif($isNight)
                            <iconify-icon icon="wi:night-clear" class="text-[#F875AA] text-[80px] sm:text-[100px]"></iconify-icon>
                        @else
                            <iconify-icon icon="wi:day-sunny" class="text-[#F875AA] text-[80px] sm:text-[100px]"></iconify-icon>
                        @endif

                        <div class="flex flex-col justify-center">
                            <span class="text-[36px] sm:text-[50px] font-bold block leading-none">{{ $bmkgParams['t'] ?? '--' }}°C</span>
                            <span class="text-[20px] sm:text-[25px] font-medium text-gray-500 mt-1">{{ $bmkgParams['weather_desc'] ?? '--' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-[40px] w-full h-full justify-between transition-all duration-300">
                <div class="bg-white rounded-[30px] p-6 h-[160px] flex items-center gap-6 shadow-sm">
                    <iconify-icon icon="si:shield-alert-fill" class="text-[#F875AA] text-[100px] sm:text-[135px] shrink-0"></iconify-icon>
                    <div>
                        <h4 class="text-[30px] sm:text-[40px] font-bold text-black leading-tight">Siaga 2</h4>
                        <p class="text-[20px] sm:text-[25px] font-medium text-gray-500">Kondisi: waspada</p>
                    </div>
                </div>

                <div class="bg-[#177FB9] rounded-[30px] p-6 h-[350px] sm:h-[520px] flex flex-col shadow-sm flex-1">
                    <div class="flex items-center gap-2 mb-4">
                        <iconify-icon icon="mdi:location" class="text-white text-[28px]"></iconify-icon>
                        <h4 class="text-white text-[25px] font-bold">Node Location</h4>
                    </div>
                    <div class="flex-1 bg-white rounded-[10px] border-[8px] border-[#177FB9] overflow-hidden">
                        <iframe 
                            width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps?q={{ $lat }},{{ $lng }}&hl=id&z=17&output=embed"
                            class="w-full h-full">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <h5 class="text-[20px] font-medium text-black mb-6">Parameter Perkiraan Cuaca by BMKG</h5>
            <div class="flex flex-wrap gap-5 lg:gap-[40px] w-full">
                @php
                    $windDirMap = [
                        'N' => 'Utara', 'NNE' => 'Utara Timur Laut', 'NE' => 'Timur Laut', 
                        'ENE' => 'Timur Timur Laut', 'E' => 'Timur', 'ESE' => 'Timur Menenggara', 
                        'SE' => 'Tenggara', 'SSE' => 'Selatan Menenggara', 'S' => 'Selatan', 
                        'SSW' => 'Selatan Barat Daya', 'SW' => 'Barat Daya', 'WSW' => 'Barat Barat Daya', 
                        'W' => 'Barat', 'WNW' => 'Barat Barat Laut', 'NW' => 'Barat Laut', 
                        'NNW' => 'Utara Barat Laut'
                    ];

                    $wdRaw = $bmkgParams['wd'] ?? '----';
                    $windDirIndo = $windDirMap[$wdRaw] ?? $wdRaw; 

                    $bmkgItems = [
                        ['Suhu Udara', ($bmkgParams['t'] ?? '----') . '°C'],
                        ['Kelembapan Udara', ($bmkgParams['hu'] ?? '----') . '%'],
                        ['Jarak Pandang', ($bmkgParams['vs'] ?? '----') . ' km'],
                        ['Arah Angin', $windDirIndo],
                        ['Kecepatan Angin', ($bmkgParams['ws'] ?? '----') . ' km/jam'],
                        ['Kondisi Awan', $bmkgParams['weather_desc'] ?? '----'],
                    ];
                @endphp

                @foreach($bmkgItems as $item)
                <div class="bg-white rounded-[15px] h-[122px] p-6 text-center shadow-sm flex flex-col justify-center flex-1 min-w-full sm:min-w-[calc(50%-20px)] lg:min-w-[calc(33.33%-40px)] transition-all duration-300 hover:shadow-md">
                    <h3 class="text-[18px] font-bold text-black">{{ $item[0] }}</h3>
                    <p class="text-[18px] font-bold mt-2 text-black">{{ $item[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</div>

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<script>
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

    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('video-cctv');
        const videoSrc = '{{ $cctvUrl }}'; 

        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(videoSrc);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                video.play();
            });
        } 
        else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = videoSrc;
            video.addEventListener('loadedmetadata', function() {
                video.play();
            });
        }
    });

    lucide.createIcons();
</script>
@endsection