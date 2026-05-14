@extends('layouts.auth')

@section('title', 'Welcome')

@push('styles')
<style>
    /* CSS unik untuk halaman welcome */
    body { overflow: hidden; }
    .opening-container { position: relative; width: 100%; height: 100vh; background: #FFF6F6; display: flex; }
    
    .frame-pink { 
        position: absolute; width: 48.5%; height: 100%; left: -50px; 
        background: #FFDFDF; border-radius: 0 150px 150px 0; z-index: 1; 
    }

    .frame-biru { 
        position: relative; width: 45%; height: 100%; background: #AEDEFC; 
        border-radius: 0 150px 150px 0; display: flex; flex-direction: column; 
        align-items: center; padding-top: 325px; z-index: 2; 
    }

    .right-content { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 3; }
    .welcome-title { font-weight: 600; font-size: 50px; color: #177FB9; margin: 0; }
    .welcome-subtitle { font-weight: 400; font-size: 30px; color: #000000; margin: 20px 0 50px 0; }
    
    .btn-rect { 
        width: 507px; height: 90px; background: #FFC7ED; box-shadow: 2px 5px 0px #F875AA; 
        border-radius: 100px; text-decoration: none; display: flex; align-items: center; justify-content: center; 
        transition: 0.3s; 
    }
    .btn-rect:hover { transform: scale(1.05); }
    .btn-text { font-weight: 400; font-size: 25px; color: #000000; }
    .logo-lingkaran { width: 225px; margin-bottom: 5px; filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25)); }
    .logo-tulisan { width: 400px; }
</style>
@endpush

@section('content')
<div class="opening-container">
    <div class="frame-pink"></div>

    <div class="frame-biru">
        <img src="{{ asset('images/Logo Makesens Lingkaran.png') }}" class="logo-lingkaran" alt="Logo Icon">
        <img src="{{ asset('images/Logo Makesens.png') }}" class="logo-tulisan" alt="Logo Text">
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