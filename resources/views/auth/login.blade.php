@extends('layouts.auth')

@section('title', 'Log In')

@push('styles')
<style>
    body { background-color: #FFF6F6; margin: 0; overflow: hidden; font-family: 'Poppins'; }
    .canvas-figma { position: relative; width: 100%; height: 100vh; overflow: hidden; }
    .frame-2 { position: absolute; width: 780px; height: 1063px; right: -4px; top: -12px; background: #FFDFDF; border-radius: 150px; z-index: 1; }
    .frame-3 { position: absolute; width: 818px; height: 1065px; right: -60px; top: -12px; background: #AEDEFC; border-radius: 150px 0 0 150px; z-index: 2; }
    .input-makesens { box-sizing: border-box; position: absolute; width: 507px; height: 90px; background: #FFFFFF; border: 3px solid #F875AA; border-radius: 100px; padding: 0 40px; font-size: 20px; outline: none; }
    .error-text { position: absolute; left: 200px; top: 295px; color: #E74C3C; font-size: 18px; font-weight: 500; z-index: 5; }
    .btn-makesens { position: absolute; width: 175px; height: 50px; left: 352px; top: 635px; background: #FFC7ED; box-shadow: 2px 5px 0px #F875AA; border-radius: 100px; border: none; cursor: pointer; font-size: 25px; display: flex; align-items: center; justify-content: center; z-index: 10; }
    input::-ms-reveal { display: none; }
</style>
@endpush

@section('content')
<div class="canvas-figma">
    <h1 style="position: absolute; left: 200px; top: 151px; font-weight: 600; font-size: 50px; color: #177FB9; margin: 0;">Log In</h1>
    <p style="position: absolute; left: 200px; top: 226px; font-size: 30px; color: #000000; margin: 0;">Here you log in securely</p>

    @if ($errors->any())
        <div class="error-text">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" placeholder="Email" required style="left: 200px; top: 357px;" class="input-makesens" value="{{ old('email') }}">
        
        <div style="position: absolute; left: 200px; top: 496px; width: 507px; height: 90px; display: flex; align-items: center;">
            <input type="password" name="password" id="password" placeholder="Password" required class="input-makesens" style="position: static; width: 100%;">
            <span id="togglePassword" style="position: absolute; right: 35px; cursor: pointer; color: #F875AA; z-index: 20;">
                <i data-lucide="eye" style="width: 28px; height: 28px; stroke-width: 2.5;"></i>
            </span>
        </div>

        <button type="submit" class="btn-makesens">Log In</button>
    </form>

    <div class="frame-2"></div>
    <div class="frame-3">
        <div class="absolute top-12 right-24 z-20">
            <img src="{{ asset('images/Logo Makesens.png') }}" alt="Logo" class="h-24">
        </div>
        <div class="flex items-center justify-center h-full w-full">
            <img src="{{ asset('images/freepik_login.png') }}" alt="Illustration" class="w-2/3 drop-shadow-xl mb-29">
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