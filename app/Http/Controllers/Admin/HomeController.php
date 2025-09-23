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
        $user = auth()->user();
        
        // Rol bazlı dashboard verileri
        if ($user->isAdmin()) {
            return $this->getAdminDashboard();
        } elseif ($user->isTeamLeader()) {
            return $this->getTeamLeaderDashboard();
        } else {
            return $this->getMemberDashboard();
        }
    }
    
    private function getAdminDashboard()
    {
        // Admin için tam yetki ile tüm veriler
        $stats = $this->getDashboardStats();
        $recentActivities = $this->getRecentActivities();
        $criticalStocks = $this->getCriticalStocks();
        $todayStats = $this->getTodayStats();
        $notifications = $this->getNotifications();
        $weather = $this->getWeatherInfo();
        
        return view('admin.home.admin', compact(
            'stats', 
            'recentActivities', 
            'criticalStocks', 
            'todayStats',
            'notifications',
            'weather'
        ));
    }
    
    private function getTeamLeaderDashboard()
    {
        // Ekip yetkilisi için ekip yönetimi odaklı veriler
        $stats = $this->getTeamLeaderStats();
        $recentActivities = $this->getTeamLeaderActivities();
        $criticalStocks = $this->getCriticalStocks();
        $teamStats = $this->getTeamStats();
        $notifications = $this->getTeamLeaderNotifications();
        
        return view('admin.home.team-leader', compact(
            'stats', 
            'recentActivities', 
            'criticalStocks', 
            'teamStats',
            'notifications'
        ));
    }
    
    private function getMemberDashboard()
    {
        // Üye için kişisel odaklı veriler
        $user = auth()->user();
        $myAssignments = $this->getMyAssignments($user);
        $recentEquipment = $this->getMyRecentEquipment($user);
        $myStats = $this->getMyStats($user);
        $quickActions = $this->getMemberQuickActions();
        
        return view('admin.home.member', compact(
            'myAssignments',
            'recentEquipment',
            'myStats',
            'quickActions'
        ));
    }
    
    private function getDashboardStats()
    {
        $totalEquipment = Equipment::count();
        $activeUsers = User::count();
        $pendingFaults = 0; // Fault modeli henüz oluşturulmamış
        
        // Gerçek kritik stok sayısını hesapla (Equipment tablosundaki critical_level ile)
        $criticalStocks = Equipment::with('stocks')
            ->whereHas('stocks', function($query) {
                $query->where('quantity', '>', 0);
            })
            ->get()
            ->filter(function ($equipment) {
                $totalQuantity = $equipment->stocks->sum('quantity');
                $criticalLevel = $equipment->critical_level ?? 0;
                
                // Kritik seviye 0 ise varsayılan 1 kabul et
                if ($criticalLevel <= 0) {
                    $criticalLevel = 1;
                }
                
                // Stok miktarı kritik seviyenin altındaysa kritik
                return $totalQuantity < $criticalLevel;
            })
            ->count();
        
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
        // Son 5 işlemi getir (ekipman ekleme, arıza bildirimi, vs.)
        $activities = collect();
        
        // Son eklenen ekipmanlar
        $recentEquipment = Equipment::with('category')
            ->latest()
            ->limit(3)
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
            ->take(5)
            ->values();
        
        return $activities;
    }
    
    private function getCriticalStocks()
    {
        return Equipment::with(['category', 'stocks'])
            ->whereHas('stocks', function($query) {
                $query->where('quantity', '>', 0);
            })
            ->get()
            ->filter(function ($equipment) {
                $totalQuantity = $equipment->stocks->sum('quantity');
                $criticalLevel = $equipment->critical_level ?? 0;
                
                // Kritik seviye 0 ise varsayılan 1 kabul et
                if ($criticalLevel <= 0) {
                    $criticalLevel = 1;
                }
                
                // Stok miktarı kritik seviyenin altındaysa kritik
                return $totalQuantity < $criticalLevel;
            })
            ->map(function ($equipment) {
                $totalQuantity = $equipment->stocks->sum('quantity');
                $criticalLevel = $equipment->critical_level ?? 1;
                
                return [
                    'equipment_name' => $equipment->name,
                    'critical_level' => $this->getCriticalLevel($totalQuantity, $criticalLevel),
                    'quantity' => $totalQuantity,
                    'critical_threshold' => $criticalLevel,
                    'last_used' => $equipment->updated_at ? $equipment->updated_at->format('d-m-Y') : 'Bilinmiyor',
                    'category' => $equipment->category->name ?? 'Kategori Yok',
                    'equipment_id' => $equipment->id
                ];
            })
            ->sortBy('quantity')
            ->take(15)
            ->values();
    }
    
    private function getCriticalLevel($quantity, $criticalLevel)
    {
        if ($quantity <= 0) return 1; // Tükendi - Kritik
        if ($quantity < $criticalLevel * 0.5) return 1; // Kritik seviyenin yarısından az - Kritik
        if ($quantity < $criticalLevel) return 2; // Kritik seviyenin altında - Dikkat
        return 3; // Normal
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
    
    // Üye rolü için özel metodlar
    private function getMyAssignments($user)
    {
        return Assignment::with(['items.equipment', 'items.equipment.category'])
            ->where('user_id', $user->id)
            ->where('status', 1) // 1 = Aktif (Geldi)
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($assignment) {
                $firstItem = $assignment->items->first();
                return [
                    'id' => $assignment->id,
                    'equipment_name' => $firstItem->equipment->name ?? 'Bilinmiyor',
                    'category' => $firstItem->equipment->category->name ?? 'Kategori Yok',
                    'assigned_date' => $assignment->created_at->format('d-m-Y'),
                    'status' => $assignment->status ? 'Aktif' : 'Pasif',
                    'notes' => $assignment->note ?? 'Not yok'
                ];
            });
    }
    
    private function getMyRecentEquipment($user)
    {
        return Assignment::with(['items.equipment', 'items.equipment.category'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($assignment) {
                $firstItem = $assignment->items->first();
                return [
                    'id' => $assignment->id,
                    'equipment_name' => $firstItem->equipment->name ?? 'Bilinmiyor',
                    'category' => $firstItem->equipment->category->name ?? 'Kategori Yok',
                    'assigned_date' => $assignment->created_at->format('d-m-Y H:i'),
                    'status' => $assignment->status ? 'Aktif' : 'Pasif',
                    'status_badge' => $this->getAssignmentStatusBadge($assignment->status)
                ];
            });
    }
    
    private function getMyStats($user)
    {
        $totalAssignments = Assignment::where('user_id', $user->id)->count();
        $activeAssignments = Assignment::where('user_id', $user->id)->where('status', 1)->count();
        $completedAssignments = Assignment::where('user_id', $user->id)->where('status', 0)->count();
        $thisMonthAssignments = Assignment::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->count();
        
        return [
            'total_assignments' => $totalAssignments,
            'active_assignments' => $activeAssignments,
            'completed_assignments' => $completedAssignments,
            'this_month_assignments' => $thisMonthAssignments
        ];
    }
    
    private function getMemberQuickActions()
    {
        return [
            [
                'title' => 'Zimmet Al',
                'description' => 'Yeni ekipman zimmeti al',
                'icon' => 'fas fa-plus-circle',
                'color' => 'success',
                'url' => route('admin.zimmetAl')
            ],
            [
                'title' => 'Teslim Et',
                'description' => 'Ekipmanı teslim et',
                'icon' => 'fas fa-hand-holding',
                'color' => 'warning',
                'url' => route('admin.teslimEt')
            ],
            [
                'title' => 'Arıza Bildir',
                'description' => 'Ekipman arızası bildir',
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'danger',
                'url' => route('admin.fault.create')
            ],
            [
                'title' => 'Profilim',
                'description' => 'Hesap bilgilerini düzenle',
                'icon' => 'fas fa-user-circle',
                'color' => 'info',
                'url' => route('admin.profile')
            ]
        ];
    }
    
    private function getAssignmentStatusBadge($status)
    {
        // status boolean: 1 = Aktif (Geldi), 0 = Pasif (Gitti)
        if ($status) {
            return 'bg-success'; // Aktif
        } else {
            return 'bg-secondary'; // Pasif
        }
    }
    
    // Ekip yetkilisi için metodlar
    private function getTeamLeaderStats()
    {
        $totalEquipment = Equipment::count();
        $teamMembers = User::where('role', 'üye')->count();
        $criticalStocks = $this->getCriticalStocks()->count();
        $pendingAssignments = Assignment::where('status', 0)->count(); // 0 = Gitti (Beklemede)
        
        return [
            'total_equipment' => $totalEquipment,
            'team_members' => $teamMembers,
            'critical_stocks' => $criticalStocks,
            'pending_assignments' => $pendingAssignments
        ];
    }
    
    private function getTeamLeaderActivities()
    {
        // Ekip yetkilisi için ekip odaklı aktiviteler
        return collect();
    }
    
    private function getTeamStats()
    {
        // Ekip istatistikleri
        return [];
    }
    
    private function getTeamLeaderNotifications()
    {
        // Ekip yetkilisi için özel bildirimler
        return collect();
    }
    
}
