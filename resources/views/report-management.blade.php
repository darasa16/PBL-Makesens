@extends('layouts.auth') 

@section('title', 'Report Management - MAKESENSES+AI')

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
            <a href="{{ route('reports') }}" class="flex items-center gap-3 p-4 bg-[#F875AA]/20 rounded-[10px] font-bold mb-4">
                <iconify-icon icon="mage:chart-fill" class="text-[#F875AA] text-[32px] shrink-0"></iconify-icon>
                <span class="text-[20px] text-[#177FB9] sidebar-text whitespace-nowrap">Report Management</span>
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-[30px] p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
                <div class="flex justify-between items-start">
                    <h4 class="text-[18px] font-bold text-black leading-snug">Total Reports</h4>
                    <iconify-icon icon="ph:scroll-fill" class="text-[#F875AA] text-[30px] shrink-0"></iconify-icon>
                </div>
                <p class="text-[48px] font-bold text-black leading-none">145</p>
            </div>

            <div class="bg-white rounded-[30px] p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
                <div class="flex justify-between items-start">
                    <h4 class="text-[18px] font-bold text-black leading-snug">Pending Handling Status</h4>
                    <iconify-icon icon="mdi:clock" class="text-[#F875AA] text-[30px] shrink-0"></iconify-icon>
                </div>
                <p class="text-[48px] font-bold text-black leading-none">50</p>
            </div>

            <div class="bg-white rounded-[30px] p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
                <div class="flex justify-between items-start">
                    <h4 class="text-[18px] font-bold text-black leading-snug">Handling Status Processed</h4>
                    <iconify-icon icon="uim:process" class="text-[#F875AA] text-[30px] shrink-0"></iconify-icon>
                </div>
                <p class="text-[48px] font-bold text-black leading-none">45</p>
            </div>

            <div class="bg-white rounded-[30px] p-6 shadow-sm flex flex-col justify-between min-h-[140px]">
                <div class="flex justify-between items-start">
                    <h4 class="text-[18px] font-bold text-black leading-snug">Handling Status Completed</h4>
                    <iconify-icon icon="fluent-mdl2:completed-solid" class="text-[#F875AA] text-[30px] shrink-0"></iconify-icon>
                </div>
                <p class="text-[48px] font-bold text-black leading-none">50</p>
            </div>
        </div>

        <div class="bg-white rounded-[30px] p-8 shadow-sm w-full h-auto overflow-visible mb-10">    
            <div class="mb-4">
                <h2 class="text-[32px] font-bold text-black">Report Management</h2>
            </div>    
                
            <div class="flex flex-wrap items-center gap-4 mb-8 mt-6 justify-start lg:justify-end">
                <div class="flex flex-col">
                    <label class="text-sm font-bold mb-1 text-black">Node Location</label>
                    <select class="border-2 border-[#F875AA] rounded-lg px-4 py-2 font-medium text-gray-700 focus:ring-2 focus:ring-[#F875AA] w-72">
                        <option>Jl. Kalibata Timur IV</option>
                        <option>Situ Babakan</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-bold mb-1 text-black">Time Period</label>
                    <input type="date" value="2026-05-18" class="border-2 border-[#F87AAA] rounded-lg px-4 py-2 font-medium text-gray-700 focus:ring-2 focus:ring-[#F875AA]">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#AEDEFC]/20">
                            <th class="p-4 font-bold text-black border-b">No</th>
                            <th class="p-4 font-bold text-black border-b">Nama</th>
                            <th class="p-4 font-bold text-black border-b">Timestamp</th>
                            <th class="p-4 font-bold text-black border-b">Location</th>
                            <th class="p-4 font-bold text-black border-b">Description</th>
                            <th class="p-4 font-bold text-black border-b">Costs</th>
                            <th class="p-4 font-bold text-black border-b">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 font-medium">
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="p-4">001</td>
                            <td class="p-4">Dara Samsara Ayu</td>
                            <td class="p-4">2026-05-02</td>
                            <td class="p-4">Jl. Kalibata Timur IV no.1, RT.10/RW.8</td>
                            <td class="p-4">Tanggul jebol</td>
                            <td class="p-4">15.000.000</td>
                            <td class="p-4">
                                <select onchange="updateStatusColor(this)" class="bg-[#FF7D7D] text-white px-4 py-1 rounded-lg text-sm font-bold border-none cursor-pointer focus:ring-2 focus:ring-[#F875AA]">
                                    <option value="pending" class="bg-white text-black" selected>Pending</option>
                                    <option value="process" class="bg-white text-black">Process</option>
                                    <option value="complete" class="bg-white text-black">Complete</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="p-4">002</td>
                            <td class="p-4">Brigita Aminsyia K.</td>
                            <td class="p-4">2026-05-03</td>
                            <td class="p-4">Jl. Pasar Minggu Baru no. 73</td>
                            <td class="p-4">Banjir meluap</td>
                            <td class="p-4">5.000.000</td>
                            <td class="p-4">
                                <select onchange="updateStatusColor(this)" class="bg-[#7DFF7D] text-[#1D631D] px-4 py-1 rounded-lg text-sm font-bold border-none cursor-pointer focus:ring-2 focus:ring-[#F875AA]">
                                    <option value="pending" class="bg-white text-black">Pending</option>
                                    <option value="process" class="bg-white text-black">Process</option>
                                    <option value="complete" class="bg-white text-black" selected>Complete</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">003</td>
                            <td class="p-4">Yoga F.M.</td>
                            <td class="p-4">2026-05-04</td>
                            <td class="p-4">Jl. Batu No. 1</td>
                            <td class="p-4">Saluran Mampet</td>
                            <td class="p-4">700.000</td>
                            <td class="p-4">
                                <select onchange="updateStatusColor(this)" class="bg-[#FFD07D] text-[#63451D] px-4 py-1 rounded-lg text-sm font-bold border-none cursor-pointer focus:ring-2 focus:ring-[#F875AA]">
                                    <option value="pending" class="bg-white text-black">Pending</option>
                                    <option value="process" class="bg-white text-black" selected>Process</option>
                                    <option value="complete" class="bg-white text-black">Complete</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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

    // FUNGSI BARU: Mengubah warna background dan teks dropdown secara dinamis
    function updateStatusColor(selectElement) {
        const value = selectElement.value;
        
        // Reset kelas warna dasar
        selectElement.className = "px-4 py-1 rounded-lg text-sm font-bold border-none cursor-pointer focus:ring-2 focus:ring-[#F875AA]";
        
        // Set warna baru sesuai pilihan option
        if (value === 'pending') {
            selectElement.classList.add('bg-[#FF7D7D]', 'text-white');
        } else if (value === 'process') {
            selectElement.classList.add('bg-[#FFD07D]', 'text-[#63451D]');
        } else if (value === 'complete') {
            selectElement.classList.add('bg-[#7DFF7D]', 'text-[#1D631D]');
        }
    }
</script>
@endsection