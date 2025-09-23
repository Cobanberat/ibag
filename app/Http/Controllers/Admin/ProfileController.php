<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'username' => 'nullable|string|max:255|unique:users,username,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasyon hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->save();

            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil bilgileri başarıyla güncellendi.',
                    'user' => $user
                ]);
            }
            
            return back()->with('success', 'Profil bilgileri başarıyla güncellendi.');
        } catch (\Exception $e) {
            \Log::error('Profil güncelleme hatası: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'exception' => $e
            ]);
            
            $errorMessage = 'Profil güncellenirken beklenmeyen bir hata oluştu.';
            
            if ($e instanceof \Illuminate\Database\QueryException) {
                $errorMessage = 'Veritabanı hatası oluştu. Lütfen daha sonra tekrar deneyin.';
            } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                $errorMessage = 'Gönderilen veriler geçersiz. Lütfen formu kontrol edin.';
            } elseif (str_contains($e->getMessage(), 'Duplicate entry')) {
                $errorMessage = 'Bu e-posta adresi veya kullanıcı adı zaten kullanılıyor.';
            }
            
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'debug' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return back()->with('error', $errorMessage);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasyon hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator);
        }

        try {
            $user = Auth::user();
            
            // Mevcut şifreyi kontrol et
            if (!Hash::check($request->current_password, $user->password)) {
                if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mevcut şifre yanlış.'
                    ], 400);
                }
                return back()->with('error', 'Mevcut şifre yanlış.');
            }

            // Yeni şifreyi kaydet
            $user->password = Hash::make($request->password);
            $user->save();

            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Şifre başarıyla değiştirildi.'
                ]);
            }
            
            return back()->with('success', 'Şifre başarıyla değiştirildi.');
        } catch (\Exception $e) {
            \Log::error('Şifre değiştirme hatası: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            
            $errorMessage = 'Şifre değiştirilirken beklenmeyen bir hata oluştu.';
            
            if ($e instanceof \Illuminate\Database\QueryException) {
                $errorMessage = 'Veritabanı hatası oluştu. Lütfen daha sonra tekrar deneyin.';
            } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                $errorMessage = 'Gönderilen veriler geçersiz. Lütfen formu kontrol edin.';
            } elseif (str_contains($e->getMessage(), 'password')) {
                $errorMessage = 'Şifre işlemi sırasında hata oluştu.';
            }
            
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'debug' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return back()->with('error', $errorMessage);
        }
    }
}