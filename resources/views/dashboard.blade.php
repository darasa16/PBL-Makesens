@extends('layouts.auth') 

@section('title', 'Dashboard')

@push('styles')
<style>
    body { background-color: #AEDEFC; overflow-y: auto; font-family: 'Poppins', sans-serif; }
    .sidebar { width: 300px; background: #FFF6F6; height: 100vh; position: fixed; left: 0; top: 0; z-index: 50; }
    .main-content { margin-left: 300px; padding: 40px; }
    .card-sensor { background: white; border-radius: 15px; padding: 20px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s; }
    .card-sensor:hover { transform: translateY(-5px); }
    .sidebar-item { display: flex; align-items: center; padding: 15px 30px; margin: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; }
    .sidebar-active { background: #FFDFDF; color: #F875AA; }
    .sidebar-inactive { color: #177FB9; }
    .bg-makesens-blue { background-color: #177FB9; }
</style>
@endpush

@section('content')
<div class="flex">
    <aside class="sidebar flex flex-col py-8">
        <div class="px-8 mb-12">
            <img src="{{ asset('images/Logo Makesens.png') }}" alt="Logo" class="w-48">
        </div>
        <nav class="flex-1">
            <a href="{{ route('dashboard') }}" class="sidebar-item sidebar-active">
                <i data-lucide="layout-dashboard" class="mr-4"></i> Dashboard
            </a>
            <a href="#" class="sidebar-item sidebar-inactive">
                <i data-lucide="brain-circuit" class="mr-4"></i> Intelligent Analysis
            </a>
            <a href="#" class="sidebar-item sidebar-inactive">
                <i data-lucide="bar-chart-3" class="mr-4"></i> Report Management
            </a>
            <a href="#" class="sidebar-item sidebar-inactive">
                <i data-lucide="history" class="mr-4"></i> History & Reports
            </a>
        </nav>
        
        <div class="px-4 mt-auto mb-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-item sidebar-inactive w-full text-left">
                    <i data-lucide="log-out" class="mr-4"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content w-full">
        <header class="flex justify-between items-center mb-10">
            <div class="flex items-center space-x-4">
                <span class="text-xl font-medium text-blue-900" id="live-clock">{{ now()->format('h:i:s A') }}</span>
                <span class="text-xl font-medium text-blue-900">{{ now()->format('l, d F Y') }}</span>
                <i data-lucide="bell" class="text-blue-900 w-6 h-6 cursor-pointer"></i>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="font-semibold text-blue-900 leading-none">Hai, {{ session('firebase_user')['name'] ?? 'Dara Samsara Ayu' }}</p>
                    <small class="text-blue-700 text-xs italic">Operator MAKESENSES+AI</small>
                </div>
                <img src="{{ asset('images/avatar.png') }}" class="w-12 h-12 rounded-full border-2 border-white shadow object-cover">
            </div>
        </header>

        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="card-sensor">
                <p class="text-gray-500 font-bold text-sm mb-2 uppercase">Suhu Udara</p>
                <h3 class="text-2xl font-bold text-blue-900">{{ $latest['suhu'] ?? '-' }}°C</h3>
            </div>
            <div class="card-sensor">
                <p class="text-gray-500 font-bold text-sm mb-2 uppercase">Kelembaban Udara</p>
                <h3 class="text-2xl font-bold text-blue-900">{{ $latest['kelembapan'] ?? '-' }}%</h3>
            </div>
            <div class="card-sensor">
                <p class="text-gray-500 font-bold text-sm mb-2 uppercase">Tekanan Udara</p>
                <h3 class="text-2xl font-bold text-blue-900">{{ $latest['tekanan'] ?? '-' }} hPa</h3>
            </div>
            <div class="card-sensor">
                <p class="text-gray-500 font-bold text-sm mb-2 uppercase">Jarak Permukaan Air</p>
                <h3 class="text-2xl font-bold text-blue-900">{{ $latest['jarak_air'] ?? '-' }} cm</h3>
            </div>
            <div class="card-sensor">
                <p class="text-gray-500 font-bold text-sm mb-2 uppercase">Laju Aliran Sungai</p>
                <h3 class="text-2xl font-bold text-blue-900">{{ $latest['flow'] ?? '-' }} L/m</h3>
            </div>
            <div class="card-sensor">
                <p class="text-gray-500 font-bold text-sm mb-2 uppercase">Total Curah Hujan</p>
                <h3 class="text-2xl font-bold text-blue-900">{{ $latest['rain_total'] ?? '-' }} mm</h3>
            </div>
            <div class="card-sensor">
                <p class="text-gray-500 font-bold text-sm mb-2 uppercase">Intensitas Hujan</p>
                <h3 class="text-2xl font-bold text-blue-900">{{ $latest['rain_rate'] ?? '-' }} mm/h</h3>
            </div>
            <div class="card-sensor">
                <p class="text-gray-500 font-bold text-sm mb-2 uppercase">Status Level Air</p>
                <h3 class="text-2xl font-bold text-pink-500">{{ $latest['float_level'] ?? 'Aman' }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="col-span-1 bg-makesens-blue rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center mb-4"><i data-lucide="video" class="mr-2"></i> CCTV Live Stream</div>
                <div class="bg-black/20 w-full h-64 rounded-lg flex flex-col items-center justify-center border-2 border-dashed border-white/30">
                    <i data-lucide="video-off" class="w-12 h-12 mb-2 text-white/50"></i>
                    <p class="text-white/70 italic text-sm text-center px-4">Stream Offline. Menunggu koneksi ESP32-CAM.</p>
                </div>
            </div>

            <div class="col-span-1 flex flex-col space-y-6">
                <div class="bg-white rounded-2xl p-8 flex items-center shadow-lg border-l-8 border-pink-400">
                    <div class="bg-pink-100 p-4 rounded-full mr-6">
                        <i data-lucide="shield-alert" class="text-pink-500 w-12 h-12"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">Siaga 2</h2>
                        <p class="text-xl text-gray-500">Kondisi: Waspada</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <p class="font-bold text-blue-900 mb-4 uppercase text-xs tracking-wider">Weather Status</p>
                    <div class="flex items-center justify-around">
                        <i data-lucide="cloud-sun" class="text-pink-400 w-16 h-16"></i>
                        <div>
                            <span class="text-4xl font-black text-gray-800">
                                {{ $bmkgParams['t'] ?? '--' }}°C
                            </span>
                            <p class="text-gray-500 font-medium">
                                {{ $bmkgParams['weather_desc'] ?? 'Memuat data...' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-1 bg-makesens-blue rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center mb-4"><i data-lucide="map-pin" class="mr-2"></i> Node Location</div>
                <iframe 
                    class="w-full h-64 rounded-lg shadow-inner bg-white"
                    frameborder="0" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://maps.google.com/maps?q={{ $lat }},{{ $lng }}&hl=id&z=15&output=embed">
                </iframe>
                <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}" 
                   target="_blank" 
                   class="mt-4 block text-center text-xs bg-white/20 hover:bg-white/30 py-2 rounded-lg transition font-semibold uppercase">
                   Klik untuk Navigasi (Google Maps)
                </a>
            </div>
        </div>

        <div class="mt-10">
            <h2 class="text-2xl font-bold text-blue-900 mb-6 flex items-center">
                <i data-lucide="wind" class="mr-3"></i> Parameter by BMKG
            </h2>
            <div class="grid grid-cols-3 gap-6">
                <div class="card-sensor bg-blue-50/50">
                    <p class="text-gray-500 font-bold mb-2">Suhu Udara</p>
                    <h3 class="text-2xl font-bold text-blue-900">{{ $bmkgParams['t'] ?? '----' }}°C</h3>
                </div>
                <div class="card-sensor bg-blue-50/50">
                    <p class="text-gray-500 font-bold mb-2">Kelembapan Udara</p>
                    <h3 class="text-2xl font-bold text-blue-900">{{ $bmkgParams['hu'] ?? '----' }}%</h3>
                </div>
                <div class="card-sensor bg-blue-50/50">
                    <p class="text-gray-500 font-bold mb-2">Jarak Pandang</p>
                    <h3 class="text-2xl font-bold text-blue-900">{{ $bmkgParams['vs'] ?? '----' }}</h3>
                </div>
                <div class="card-sensor bg-blue-50/50">
                    <p class="text-gray-500 font-bold mb-2">Kecepatan Angin</p>
                    <h3 class="text-2xl font-bold text-blue-900">{{ $bmkgParams['ws'] ?? '----' }} km/jam</h3>
                </div>
                <div class="card-sensor bg-blue-50/50">
                    <p class="text-gray-500 font-bold mb-2">Arah Angin</p>
                    <h3 class="text-2xl font-bold text-blue-900">{{ $bmkgParams['wd'] ?? '----' }}</h3>
                </div>
                <div class="card-sensor bg-blue-50/50">
                    <p class="text-gray-500 font-bold mb-2">Kondisi Awan</p>
                    <h3 class="text-2xl font-bold text-blue-900">{{ $bmkgParams['weather_desc'] ?? '----' }}</h3>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Pastikan library Lucide terload
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Jam Live
    setInterval(() => {
        const now = new Date();
        const clockElement = document.getElementById('live-clock');
        if(clockElement) {
            clockElement.innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
        }
    }, 1000);
</script>
@endsection