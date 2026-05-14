<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckFirebaseSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah ada session 'firebase_user' yang kita buat di FirebaseAuthController
        if (!Session::has('firebase_user')) {
            
            // 2. Jika tidak ada, tendang balik ke halaman login dengan pesan error
            return redirect()->route('login')->withErrors([
                'msg' => 'Silakan login terlebih dahulu untuk mengakses sistem monitoring.'
            ]);
        }

        // 3. Jika ada session, izinkan lanjut ke halaman yang dituju (Dashboard)
        return $next($request);
    }
}