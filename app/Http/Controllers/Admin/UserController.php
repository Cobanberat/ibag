<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        
        // İstatistikleri hesapla
        $stats = $this->calculateUserStats();
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $pageTitle = 'Yeni Kullanıcı Ekle';
        $roles = User::ROLE_OPTIONS;
        
        return view('admin.users.create', compact('pageTitle', 'roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'nullable|string|max:255|unique:users',
            'role' => 'required|in:admin,ekip_yetkilisi,üye',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'nullable|in:active,inactive',
            'avatar_color' => 'nullable|string|max:7',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasyon hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username ?: $request->email;
            $user->role = $request->role;
            $user->password = Hash::make($request->password);
            $user->status = $request->status ?: 'active';
            $user->avatar_color = $request->avatar_color ?: '#0d6efd';
            $user->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kullanıcı başarıyla oluşturuldu.',
                    'user' => $user
                ]);
            }
            
            return redirect()->route('admin.users')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            Log::error('Kullanıcı oluşturulurken hata: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kullanıcı oluşturulurken hata oluştu: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Kullanıcı oluşturulurken hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'role' => $user->role,
                    'status' => $user->status,
                    'created_at' => $user->created_at ? Carbon::parse($user->created_at)->format('d.m.Y H:i') : 'Tarih yok',
                    'last_login_at' => $user->last_login_at ? Carbon::parse($user->last_login_at)->format('d.m.Y H:i') : 'Hiç giriş yapmamış',
                    'avatar_color' => $user->avatar_color
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı bulunamadı: ' . $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        
        // Form verilerini al - JSON veya form data
        $data = $request->all();
        
        // Eğer JSON ile gönderilmişse, json() metodunu kullan
        if ($request->isJson()) {
            $data = $request->json()->all();
        }
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'username' => 'nullable|string|max:255|unique:users,username,' . $id,
            'role' => 'required|in:admin,ekip_yetkilisi,üye',
            'status' => 'nullable|in:active,inactive',
            'avatar_color' => 'nullable|string|max:7',
            'password' => 'nullable|string|min:8',
            'password_confirmation' => 'nullable|string|min:8|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasyon hatası',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($id);
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->username = $data['username'] ?? $data['email'];
            $user->role = $data['role'];
            $user->status = $data['status'] ?? $user->status;
            $user->avatar_color = $data['avatar_color'] ?? $user->avatar_color;
            
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Kullanıcı başarıyla güncellendi.',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Kullanıcı güncellenirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı güncellenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Kendini silmeye çalışıyorsa engelle
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kendinizi silemezsiniz!'
                ], 400);
            }
            
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kullanıcı başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Kullanıcı silinirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı silinirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Kendini pasif yapmaya çalışıyorsa engelle
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kendinizi pasif yapamazsınız!'
                ], 400);
            }
            
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Kullanıcı durumu başarıyla güncellendi.',
                'new_status' => $user->status
            ]);
        } catch (\Exception $e) {
            Log::error('Kullanıcı durumu güncellenirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı durumu güncellenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasyon hatası',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userIds = $request->user_ids;
            
            // Kendini silmeye çalışıyorsa engelle
            if (in_array(auth()->id(), $userIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kendinizi silemezsiniz!'
                ], 400);
            }
            
            User::whereIn('id', $userIds)->delete();

            return response()->json([
                'success' => true,
                'message' => count($userIds) . ' kullanıcı başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Toplu kullanıcı silinirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcılar silinirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel()
    {
        try {
            $users = User::select('name', 'email', 'username', 'role', 'status', 'created_at', 'last_login_at')
                ->orderBy('created_at', 'desc')
                ->get();

            $filename = 'kullanicilar_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Excel export işlemi burada yapılacak
            // Şimdilik sadece JSON döndürüyoruz
            
            return response()->json([
                'success' => true,
                'message' => 'Excel dosyası hazırlanıyor...',
                'data' => $users,
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            Log::error('Excel export hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Excel export hatası: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateUserStats()
    {
        // Mevcut veriler
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $activeUsers = User::where('status', 'active')->count();
        
        // Bu ay eklenen kullanıcılar
        $newThisMonth = User::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count();
        
        // Geçen ay eklenen kullanıcılar
        $newLastMonth = User::whereMonth('created_at', Carbon::now()->subMonth()->month)
                           ->whereYear('created_at', Carbon::now()->subMonth()->year)
                           ->count();
        
        
        // Büyüme hesaplamaları - sadece yeni eklenen kullanıcılar için
        $newGrowth = $this->calculateGrowth($newThisMonth, $newLastMonth);
        
        // Diğer kartlar için basit büyüme (yeni kullanıcı büyümesi ile aynı)
        $totalGrowth = $newGrowth;
        $adminGrowth = $newGrowth;
        $activeGrowth = $newGrowth;
        
        return [
            'total' => $totalUsers,
            'admin' => $adminUsers,
            'active' => $activeUsers,
            'new_this_month' => $newThisMonth,
            'growth' => $totalGrowth,
            'admin_growth' => $adminGrowth,
            'active_growth' => $activeGrowth,
            'new_growth' => $newGrowth
        ];
    }
    
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        $growth = (($current - $previous) / $previous) * 100;
        return round($growth, 1);
    }

    private function generateAvatarColor()
    {
        $colors = [
            '#6366f1', '#8b5cf6', '#ec4899', '#ef4444', '#f97316',
            '#eab308', '#22c55e', '#06b6d4', '#3b82f6', '#84cc16'
        ];
        
        return $colors[array_rand($colors)];
    }
}
