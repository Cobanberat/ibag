<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Önce e-posta adresinin veritabanında olup olmadığını kontrol et
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'E-posta adresi hatalı.',
            ]);
        }

        // E-posta doğru ama şifre yanlış
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'password' => 'Şifre hatalı.',
            ]);
        }

        // Kullanıcı durumunu kontrol et
        if ($user->status !== 'active') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Hesabınız pasif durumda. Lütfen yönetici ile iletişime geçin.',
            ]);
        }
        
        $request->session()->regenerate();
        
        // Kullanıcının son giriş zamanını güncelle
        $user->update(['last_login_at' => now()]);
        
        // Admin dashboard'a yönlendir
        return redirect()->intended(route('admin.dashboard'));
    }
} 