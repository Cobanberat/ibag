<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentStock;
use App\Models\User;
use App\Models\Assignment;
// use App\Models\Fault; // Model henüz oluşturulmamış
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // KPI verilerini hesapla
        $stats = $this->getDashboardStats();
        
        // Son işlemleri getir
        $recentActivities = $this->getRecentActivities();
        
        // Kritik stok seviyesindeki ekipmanları getir
        $criticalStocks = $this->getCriticalStocks();
        
        // Bugünkü işlemleri getir
        $todayStats = $this->getTodayStats();
        
        // Kullanıcı bilgilerini getir
        $user = auth()->user();
        
        // Bildirimleri getir
        $notifications = $this->getNotifications();
        
        // Hava durumu bilgisi (şimdilik statik, API entegrasyonu yapılabilir)
        $weather = $this->getWeatherInfo();
        
        return view('admin.home.index', compact(
            'stats', 
            'recentActivities', 
            'criticalStocks', 
            'todayStats',
            'user',
            'notifications',
            'weather'
        ));
    }
    
    private function getDashboardStats()
    {
        $totalEquipment = Equipment::count();
        $activeUsers = User::count();
        $pendingFaults = 0; // Fault modeli henüz oluşturulmamış
        $criticalStocks = EquipmentStock::where('quantity', '<=', 5)->count();
        
        // Bugünkü artışları hesapla
        $todayEquipment = Equipment::whereDate('created_at', today())->count();
        $todayUsers = User::whereDate('created_at', today())->count();
        $criticalFaults = 0; // Fault modeli henüz oluşturulmamış
        
        return [
            'total_equipment' => $totalEquipment,
            'active_users' => $activeUsers,
            'pending_faults' => $pendingFaults,
            'critical_stocks' => $criticalStocks,
            'today_equipment' => $todayEquipment,
            'today_users' => $todayUsers,
            'critical_faults' => $criticalFaults,
        ];
    }
    
    private function getRecentActivities()
    {
        // Son 10 işlemi getir (ekipman ekleme, arıza bildirimi, vs.)
        $activities = collect();
        
        // Son eklenen ekipmanlar
        $recentEquipment = Equipment::with('category')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($equipment) {
                return [
                    'id' => $equipment->id,
                    'date' => $equipment->created_at,
                    'action' => 'Ekipman Eklendi',
                    'user' => 'Sistem',
                    'detail' => $equipment->name . ' - ' . ($equipment->category->name ?? 'Kategori Yok'),
                    'description' => 'Yeni ekipman kaydı',
                    'type' => 'Donanım',
                    'badge_class' => 'bg-success'
                ];
            });
        
        // Son arıza bildirimleri (Fault modeli henüz oluşturulmamış)
        $recentFaults = collect();
        
        // Son kullanıcı kayıtları
        $recentUsers = User::latest()
            ->limit(2)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'date' => $user->created_at,
                    'action' => 'Kullanıcı Eklendi',
                    'user' => $user->name,
                    'detail' => 'Yeni kullanıcı kaydı',
                    'description' => 'Kullanıcı ekleme',
                    'type' => 'Yönetim',
                    'badge_class' => 'bg-info'
                ];
            });
        
        // Tüm aktiviteleri birleştir ve tarihe göre sırala
        $activities = $activities
            ->merge($recentEquipment)
            ->merge($recentFaults)
            ->merge($recentUsers)
            ->sortByDesc('date')
            ->take(10)
            ->values();
        
        return $activities;
    }
    
    private function getCriticalStocks()
    {
        return EquipmentStock::with(['equipment', 'equipment.category'])
            ->where('quantity', '<=', 5)
            ->orderBy('quantity', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($stock) {
                return [
                    'equipment_name' => $stock->equipment->name ?? 'Bilinmiyor',
                    'critical_level' => $this->getCriticalLevel($stock->quantity),
                    'quantity' => $stock->quantity,
                    'last_used' => $stock->updated_at ? $stock->updated_at->format('d-m-Y') : 'Bilinmiyor',
                    'category' => $stock->equipment->category->name ?? 'Kategori Yok'
                ];
            });
    }
    
    private function getCriticalLevel($quantity)
    {
        if ($quantity <= 1) return 1; // Kritik
        if ($quantity <= 3) return 2; // Dikkat
        if ($quantity <= 5) return 3; // Uyarı
        return 4; // Normal
    }
    
    private function getTodayStats()
    {
        $today = today();
        
        return [
            'equipment_added' => Equipment::whereDate('created_at', $today)->count(),
            'users_added' => User::whereDate('created_at', $today)->count(),
            'faults_reported' => 0, // Fault modeli henüz oluşturulmamış
            'assignments_created' => Assignment::whereDate('created_at', $today)->count(),
        ];
    }
    
    public function getStats()
    {
        $stats = $this->getDashboardStats();
        return response()->json($stats);
    }
    
    private function getNotifications()
    {
        $notifications = collect();
        
        // Kritik stok uyarıları
        $criticalStocks = EquipmentStock::where('quantity', '<=', 5)->count();
        if ($criticalStocks > 0) {
            $notifications->push([
                'id' => 1,
                'type' => 'warning',
                'icon' => 'fa-exclamation-circle',
                'title' => 'Kritik stok azaldı!',
                'message' => $criticalStocks . ' ekipman kritik seviyede',
                'time' => now()->diffForHumans(),
                'url' => route('admin.stock')
            ]);
        }
        
        // Yeni arıza bildirimleri (Fault modeli henüz oluşturulmamış)
        $newFaults = 0; // Fault::where('status', 'pending')->count();
        if ($newFaults > 0) {
            $notifications->push([
                'id' => 2,
                'type' => 'danger',
                'icon' => 'fa-bug',
                'title' => 'Yeni arıza bildirimi',
                'message' => $newFaults . ' yeni arıza bekliyor',
                'time' => now()->diffForHumans(),
                'url' => route('admin.fault')
            ]);
        }
        
        // Yeni kullanıcı kayıtları
        $newUsers = User::whereDate('created_at', today())->count();
        if ($newUsers > 0) {
            $notifications->push([
                'id' => 3,
                'type' => 'success',
                'icon' => 'fa-user-plus',
                'title' => 'Yeni kullanıcı eklendi',
                'message' => $newUsers . ' yeni kullanıcı bugün eklendi',
                'time' => now()->diffForHumans(),
                'url' => route('admin.users')
            ]);
        }
        
        return $notifications;
    }
    
    private function getWeatherInfo()
    {
        // Şimdilik statik veri, daha sonra hava durumu API'si entegre edilebilir
        return [
            'city' => 'Konya',
            'temperature' => '24°C',
            'condition' => 'Parçalı Bulutlu',
            'icon' => 'fa-cloud-sun'
        ];
    }
    
}
