<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Session;

class FirebaseAuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        try {
            // 1. Verifikasi Email & Password ke Firebase Auth
            $auth = Firebase::auth();
            $signInResult = $auth->signInWithEmailAndPassword($request->email, $request->password);
            
            // 2. Cek apakah email terdaftar di data Nasya (Realtime Database)
            $database = Firebase::database();
            $users = $database->getReference('node1/latest/user')->getValue();
            
            $authorizedUser = null;
            if ($users) {
                foreach ($users as $userData) {
                    if (isset($userData['email']) && $userData['email'] == $request->email) {
                        $authorizedUser = $userData;
                        break;
                    }
                }
            }

            // 3. Jika email ada di daftar Nasya, izinkan masuk
            if ($authorizedUser) {
                Session::put('firebase_user', $authorizedUser);
                Session::put('user_id', $signInResult->firebaseUserId());
                return redirect('/dashboard');
            } else {
                return back()->withErrors(['msg' => 'Akun valid, tapi email ini tidak terdaftar sebagai Admin di sistem.']);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Email atau Password salah!']);
        }
    }

    public function logout() {
        Session::flush();
        return redirect('/login');
    }
}