@extends('layouts.auth')

@section('title', 'Welcome')

@push('styles')
<style>
    body { overflow-x: hidden; }
    
    /* Container utama pakai flex-col untuk HP, flex-row untuk laptop */
    .opening-container { 
        position: relative; 
        width: 100%; 
        min-height: 100vh; 
        background: #FFF6F6; 
        display: flex; 
        flex-direction: column;
    }
    
    /* Sisi Kiri (Frame Pink & Biru) */
    .left-section {
        position: relative;
        width: 100%;
        min-height: 40vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .frame-pink { 
        position: absolute; 
        width: 100%; 
        height: 100%; 
        top: -20px; 
        left: 0;
        background: #FFDFDF; 
        border-radius: 0 0 50px 50px; 
        z-index: 1; 
    }

    .frame-biru { 
        position: relative; 
        width: 90%; 
        height: 90%; 
        background: #AEDEFC; 
        border-radius: 0 0 50px 50px; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center;
        padding: 40px 20px;
        z-index: 2; 
    }

    /* Sisi Kanan / Konten Teks */
    .right-content { 
        flex: 1; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        padding: 40px 20px;
        z-index: 3; 
    }
    
    /* Font dibuat responsif dengan clamp agar otomatis mengecil di HP */
    .welcome-title { 
        font-weight: 600; 
        font-size: clamp(32px, 4vw, 50px); 
        color: #177FB9; 
        margin: 0; 
        text-align: center;
    }
    
    .welcome-subtitle { 
        font-weight: 400; 
        font-size: clamp(18px, 2.5vw, 30px); 
        color: #000000; 
        margin: 15px 0 35px 0; 
        text-align: center;
    }
    
    /* Button adaptif (max-width menjaganya agar tidak kepanjangan di laptop) */
    .btn-rect { 
        width: 100%;
        max-width: 380px; 
        height: clamp(60px, 8vh, 90px); 
        background: #FFC7ED; 
        box-shadow: 2px 5px 0px #F875AA; 
        border-radius: 100px; 
        text-decoration: none; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        transition: 0.3s; 
    }
    .btn-rect:hover { transform: scale(1.05); }
    .btn-text { font-weight: 400; font-size: clamp(18px, 2vw, 25px); color: #000000; }
    
    /* Ukuran logo dibuat fleksibel */
    .logo-lingkaran { 
        width: clamp(120px, 15vw, 225px); 
        margin-bottom: 10px; 
        filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25)); 
    }
    .logo-tulisan { 
        width: 100%; 
        max-width: 400px; 
        padding: 0 20px;
    }

    /* --- MEDIA QUERY: KHUSUS TAMPILAN LAPTOP / MONITOR BESAR --- */
    @media (min-width: 1024px) {
        .opening-container { flex-direction: row; }
        
        .left-section {
            width: 45.5%;
            height: 100vh;
        }

        .frame-pink { 
            width: 100%; 
            height: 100%; 
            right: 35px;
            left: auto; 
            top: 0;
            border-radius: 0 150px 150px 0; 
        }

        .frame-biru { 
            width: 93%; 
            height: 100%; 
            border-radius: 0 150px 150px 0; 
            padding-top: 0; /* Menggunakan flex center bawaan untuk meluruskan logo */
            margin-right: auto;
        }
        
        .right-content {
            padding: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="opening-container">
    <div class="left-section">
        <div class="frame-pink"></div>
        <div class="frame-biru">
            <img src="{{ asset('images/Logo Makesens Lingkaran.png') }}" class="logo-lingkaran" alt="Logo Icon">
            <img src="{{ asset('images/Logo Makesens.png') }}" class="logo-tulisan" alt="Logo Text">
        </div>
    </div>

    <div class="right-content">
        <h1 class="welcome-title">Welcome MakeSens!</h1>
        <p class="welcome-subtitle">Do You Have An Account?</p>

        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-rect">
                    <span class="btn-text">Go to Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-rect">
                    <span class="btn-text">Log In</span>
                </a>
            @endauth
        @endif
    </div>
</div>
@endsection