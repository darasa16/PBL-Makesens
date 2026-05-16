@extends('layouts.auth')

@section('title', 'Log In')

@push('styles')
<style>
    body { background-color: #FFF6F6; margin: 0; min-height: 100vh; font-family: 'Poppins'; overflow-x: hidden; }
    
    /* Container Utama Responsif */
    .canvas-figma { 
        display: flex; 
        flex-direction: column-reverse; /* Di HP: form di bawah, ilustrasi di atas */
        min-height: 100vh; 
        width: 100%;
        position: relative;
    }
    
    /* Sisi Kiri (Form Login) */
    .form-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 40px 20px;
        z-index: 10;
    }
    
    .form-container {
        width: 100%;
        max-width: 480px; 
        margin: 0 auto;  
    }

    /* Sisi Kanan (Visual Section disamakan dengan welcome.blade.php) */
    .visual-section {
        position: relative;
        width: 100%;
        min-height: 40vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Frame Pink disamakan posisinya */
    .frame-2 { 
        position: absolute; 
        width: 100%; 
        height: 100%; 
        top: -20px; 
        left: 0; 
        background: #FFDFDF; 
        border-radius: 0 0 50px 50px; 
        z-index: 1; 
    }

    /* Frame Biru disamakan posisinya */
    .frame-3 { 
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
    
    .input-wrapper {
        position: relative;
        width: 100%;
        margin-bottom: 25px;
    }

    .input-makesens { 
        box-sizing: border-box; 
        width: 100%; 
        height: 55px; 
        background: #FFFFFF; 
        border: 3px solid #F875AA; 
        border-radius: 100px; 
        padding: 0 30px; 
        font-size: 16px; 
        outline: none; 
    }
    
    .error-text { 
        color: #E74C3C; 
        font-size: 16px; 
        font-weight: 500; 
        margin-bottom: 20px;
    }
    
    .btn-makesens { 
        width: 100%;
        max-width: 175px; 
        height: 50px; 
        background: #FFC7ED; 
        box-shadow: 2px 5px 0px #F875AA; 
        border-radius: 100px; 
        border: none; 
        cursor: pointer; 
        font-size: 22px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin-top: 15px;
        transition: transform 0.2s;
    }
    .btn-makesens:hover { transform: scale(1.05); }
    
    input::-ms-reveal { display: none; }

    /* --- MEDIA QUERY: DISAMAKAN SKALANYA DENGAN WELCOME DI LAPTOP --- */
    @media (min-width: 1024px) {
        .canvas-figma { flex-direction: row; overflow: hidden; height: 100vh; }
        
        .form-section {
            padding-left: 10%; 
            align-items: flex-start;
        }
        
        .form-container {
            margin: 0; 
        }
        
        .input-makesens {
            height: 90px; 
            font-size: 20px;
            padding: 0 40px;
        }

        /* Lebar section visual disamakan dengan welcome (48.5%) */
        .visual-section {
            width: 45.5%; 
            height: 100vh;
            position: absolute;
            right: 0; /* Dikunci tetep di sebelah KANAN layar */
            top: 0;
        }

        /* Frame Pink (Ukurannya sama dengan welcome, tapi nempel di KANAN) */
        .frame-2 { 
            width: 100%; 
            height: 100%; 
            right: -35px; /* Sesuai setelan mundur yang kamu mau kemarin */
            left: auto;   
            top: 0;
            border-radius: 150px 0 0 150px; /* Melengkung ke arah kiri */
        }

        /* Frame Biru (Ukurannya sama dengan welcome, skala 93%) */
        .frame-3 { 
            width: 93%; 
            height: 100%; 
            border-radius: 150px 0 0 150px; /* Melengkung ke arah kiri */
            padding-top: 0; 
            margin-left: auto; /* Mendorong frame biru agar mepet ke kanan */
            margin-right: 0;
        }
        
        .btn-makesens {
            align-self: center; 
        }
    }
</style>
@endpush

@section('content')
<div class="canvas-figma">
    
    <div class="form-section">
        <div class="form-container">
            <h1 class="font-semibold text-[clamp(36px,4vw,50px)] text-[#177FB9] leading-tight m-0">Log In</h1>
            <p class="text-[clamp(18px,2vw,30px)] text-black mt-2 mb-8 m-0">Here you log in securely</p>

            @if ($errors->any())
                <div class="error-text">
                    @foreach ($errors->all() as $error)
                        <p class="m-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="flex flex-col">
                @csrf
                
                <div class="input-wrapper">
                    <input type="email" name="email" placeholder="Email" required class="input-makesens" value="{{ old('email') }}">
                </div>
                
                <div class="input-wrapper flex items-center">
                    <input type="password" name="password" id="password" placeholder="Password" required class="input-makesens">
                    <span id="togglePassword" class="absolute right-[35px] cursor-pointer text-[#F875AA] z-20">
                        <i data-lucide="eye" style="width: 28px; height: 28px; stroke-width: 2.5;"></i>
                    </span>
                </div>

                <button type="submit" class="btn-makesens">Log In</button>
            </form>
        </div>
    </div>

    <div class="visual-section">
        <div class="frame-2"></div>
        <div class="frame-3">
            <div class="absolute top-8 right-12 lg:top-8 lg:right-10 z-20">
                <img src="{{ asset('images/Logo Makesens.png') }}" alt="Logo" class="h-16 lg:h-20">
            </div>
            <div class="flex items-center justify-center h-full w-full p-6 lg:p-0">
                <img src="{{ asset('images/freepik_login.png') }}" alt="Illustration" class="w-full max-w-[450px] lg:max-w-none lg:w-2/3 drop-shadow-xl">
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = `<i data-lucide="${type === 'text' ? 'eye-off' : 'eye'}" style="width: 28px; height: 28px; stroke-width: 2.5;"></i>`;
        lucide.createIcons();
    });
</script>
@endpush