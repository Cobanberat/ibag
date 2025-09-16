<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EquipmentStock;
use App\Models\Fault;
use App\Models\Assignment;
use App\Models\EquipmentCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportingController extends Controller
{
    public function index()
    {
        $pageTitle = 'Raporlama & Analiz';
        
        // Genel istatistikler
        $stats = $this->getGeneralStats();
        
        // Kategori bazlı ekipman sayıları
        $equipmentByCategory = $this->getEquipmentByCategory();
        
        // Aylık arıza trendi
        $faultTrends = $this->getFaultTrends();
        $faultTrendsData = $this->getFaultTrendsData();
        
        // Kullanıcı aktivite raporu
        $userActivity = $this->getUserActivity();
        
        // Stok durumu raporu
        $stockStatus = $this->getStockStatus();
        
        // Son 30 günlük işlemler
        $recentActivities = $this->getRecentActivities();
        
        return view('admin.reporting.index', compact(
            'pageTitle', 
            'stats', 
            'equipmentByCategory', 
            'faultTrends', 
            'faultTrendsData',
            'userActivity', 
            'stockStatus', 
            'recentActivities'
        ));
    }
    
    private function getGeneralStats()
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_equipment' => EquipmentStock::count(),
            'active_equipment' => EquipmentStock::where('status', 'Aktif')->count(),
            'faulty_equipment' => EquipmentStock::where('status', 'Arızalı')->count(),
            'maintenance_required' => EquipmentStock::where('status', 'Bakım Gerekiyor')->count(),
            'total_faults' => Fault::count(),
            'pending_faults' => Fault::whereIn('status', ['Beklemede', 'İşlemde'])->count(),
            'resolved_faults' => Fault::where('status', 'Çözüldü')->count(),
            'total_assignments' => Assignment::count(),
            'active_assignments' => Assignment::where('status', 1)->count(),
        ];
    }
    
    private function getEquipmentByCategory()
    {
        return EquipmentStock::with('equipment.category')
            ->get()
            ->groupBy('equipment.category.name')
            ->map(function ($items, $categoryName) {
                return [
                    'name' => $categoryName,
                    'total' => $items->count(),
                    'active' => $items->where('status', 'Aktif')->count(),
                    'faulty' => $items->where('status', 'Arızalı')->count(),
                    'maintenance' => $items->where('status', 'Bakım Gerekiyor')->count(),
                    'equipment' => $items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->equipment->name ?? 'Bilinmeyen',
                            'code' => $item->code ?? 'N/A',
                            'status' => $item->status ?? 'Bilinmiyor',
                            'location' => $item->location ?? 'Belirtilmemiş',
                            'updated_at' => $item->updated_at,
                        ];
                    })->toArray()
                ];
            })
            ->values() // Collection'ı array'e çevir
            ->toArray(); // Array'e çevir
    }
    
    private function getFaultTrends()
    {
        $last6Months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $last6Months[] = [
                'month' => $date->format('M Y'),
                'faults' => Fault::whereYear('reported_date', $date->year)
                    ->whereMonth('reported_date', $date->month)
                    ->count(),
                'resolved' => Fault::whereYear('resolved_date', $date->year)
                    ->whereMonth('resolved_date', $date->month)
                    ->count(),
            ];
        }
        return $last6Months;
    }
    
    private function getFaultTrendsData()
    {
        $trends = $this->getFaultTrends();
        return [
            'labels' => array_column($trends, 'month'),
            'faults' => array_column($trends, 'faults'),
            'resolved' => array_column($trends, 'resolved'),
        ];
    }
    
    private function getUserActivity()
    {
        return User::withCount(['assignments as total_assignments'])
            ->orderBy('total_assignments', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role_label,
                    'assignments' => $user->total_assignments,
                    'last_login' => $user->last_login_at ? $user->last_login_at->format('d.m.Y H:i') : 'Hiç giriş yapmamış',
                ];
            });
    }
    
    private function getStockStatus()
    {
        return [
            'total' => EquipmentStock::count(),
            'active' => EquipmentStock::where('status', 'Aktif')->count(),
            'faulty' => EquipmentStock::where('status', 'Arızalı')->count(),
            'maintenance' => EquipmentStock::where('status', 'Bakım Gerekiyor')->count(),
            'in_use' => EquipmentStock::where('status', 'Kullanımda')->count(),
            'available' => EquipmentStock::where('status', 'Müsait')->count(),
        ];
    }
    
    private function getRecentActivities()
    {
        $activities = collect();
        
        // Son arızalar
        $recentFaults = Fault::with(['equipmentStock.equipment', 'reporter'])
            ->orderBy('reported_date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($fault) {
                return [
                    'type' => 'fault',
                    'title' => 'Yeni Arıza Bildirimi',
                    'description' => $fault->equipmentStock->equipment->name . ' - ' . $fault->type,
                    'user' => $fault->reporter->name ?? 'Bilinmeyen',
                    'date' => $fault->reported_date ? Carbon::parse($fault->reported_date)->format('d.m.Y H:i') : 'Tarih yok',
                    'status' => $fault->status,
                ];
            });
        
        // Son zimmet işlemleri
        $recentAssignments = Assignment::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($assignment) {
                return [
                    'type' => 'assignment',
                    'title' => 'Zimmet İşlemi',
                    'description' => 'Zimmet işlemi - ' . ($assignment->status ? 'Aktif' : 'Pasif'),
                    'user' => $assignment->user->name ?? 'Bilinmeyen',
                    'date' => $assignment->created_at->format('d.m.Y H:i'),
                    'status' => $assignment->status ? 'Aktif' : 'Pasif',
                ];
            });
        
        return $activities->merge($recentFaults)->merge($recentAssignments)
            ->sortByDesc('date')
            ->take(10);
    }
    
    public function export(Request $request)
    {
        $type = $request->get('type', 'general');
        $format = $request->get('format', 'excel');
        
        switch ($type) {
            case 'equipment':
                return $this->exportEquipment($format);
            case 'faults':
                return $this->exportFaults($format);
            case 'users':
                return $this->exportUsers($format);
            case 'assignments':
                return $this->exportAssignments($format);
            default:
                return $this->exportGeneral($format);
        }
    }
    
    private function exportGeneral($format)
    {
        $stats = $this->getGeneralStats();
        
        if ($format === 'excel') {
            // Excel export logic here
            return response()->json(['message' => 'Excel export will be implemented']);
        }
        
        return response()->json(['message' => 'Export format not supported']);
    }
    
    private function exportEquipment($format)
    {
        // Equipment export logic here
        return response()->json(['message' => 'Equipment export will be implemented']);
    }
    
    private function exportFaults($format)
    {
        // Faults export logic here
        return response()->json(['message' => 'Faults export will be implemented']);
    }
    
    private function exportUsers($format)
    {
        // Users export logic here
        return response()->json(['message' => 'Users export will be implemented']);
    }
    
    private function exportAssignments($format)
    {
        // Assignments export logic here
        return response()->json(['message' => 'Assignments export will be implemented']);
    }
}
