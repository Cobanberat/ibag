<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fault;
use App\Models\EquipmentStock;
use App\Models\User;
use App\Models\Assignment;
use App\Models\AssignmentItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatusCheckController extends Controller
{
    public function index()
    {
        $pageTitle = 'Durum Kontrolü';
        
        // Acil durumlar - Bugün ve geçmiş tarihlerde çözülmemiş arızalar
        $acilDurumlar = $this->getAcilDurumlar();
        
        // Yapılması gerekenler - Gelecek tarihlerde planlanan işlemler
        $yapilacaklar = $this->getYapilacaklar();
        
        // İstatistikler
        $stats = $this->getStatistics();
        
        return view('admin.statusCheck.index', compact(
            'pageTitle',
            'acilDurumlar', 
            'yapilacaklar',
            'stats'
        ));
    }
    
    /**
     * Acil durumları getir
     */
    private function getAcilDurumlar()
    {
        $today = Carbon::today();
        
        return Fault::with(['equipmentStock.equipment', 'reporter', 'resolver'])
            ->whereIn('status', ['Beklemede', 'İşlemde'])
            ->where(function($query) use ($today) {
                $query->where('reported_date', '<=', $today)
                      ->orWhere('priority', 'Yüksek');
            })
            ->orderByRaw("CASE 
                WHEN priority = 'Yüksek' THEN 1 
                WHEN priority = 'Orta' THEN 2 
                WHEN priority = 'Düşük' THEN 3 
                ELSE 4 END")
            ->orderBy('reported_date', 'asc')
            ->get()
            ->map(function($fault) {
                return [
                    'id' => $fault->id,
                    'ekipman' => $fault->equipmentStock->equipment->name ?? 'Bilinmiyor',
                    'islem' => $this->getIslemText($fault->type, $fault->status),
                    'planlanan_tarih' => $fault->reported_date ? Carbon::parse($fault->reported_date)->format('d.m.Y') : '-',
                    'sorumlu' => $fault->resolver->name ?? ($fault->reporter->name ?? 'Atanmamış'),
                    'priority' => $fault->priority,
                    'status' => $fault->status,
                    'type' => $fault->type,
                    'aciliyet' => $this->getAciliyetLevel($fault)
                ];
            });
    }
    
    /**
     * Yapılması gerekenleri getir
     */
    private function getYapilacaklar()
    {
        $today = Carbon::today();
        $nextMonth = Carbon::today()->addMonth();
        
        // Planlı bakımlar ve gelecekteki işlemler
        $plannedItems = collect();
        
        // Fault tablosundan gelecekteki işlemler
        $futureFaults = Fault::with(['equipmentStock.equipment', 'reporter', 'resolver'])
            ->where('status', '!=', 'Çözüldü')
            ->where('status', '!=', 'İptal Edildi')
            ->where('reported_date', '>', $today)
            ->orderBy('reported_date', 'asc')
            ->get();
            
        foreach($futureFaults as $fault) {
            $plannedItems->push([
                'id' => $fault->id,
                'ekipman' => $fault->equipmentStock->equipment->name ?? 'Bilinmiyor',
                'islem' => $this->getIslemText($fault->type, $fault->status),
                'planlanan_tarih' => Carbon::parse($fault->reported_date)->format('d.m.Y'),
                'sorumlu' => $fault->resolver->name ?? ($fault->reporter->name ?? 'Atanmamış'),
                'priority' => $fault->priority ?? 'Orta',
                'status' => $fault->status,
                'type' => 'fault'
            ]);
        }
        
        // Yaklaşan bakım tarihleri (eğer next_maintenance_date alanı varsa)
        $upcomingMaintenance = EquipmentStock::with(['equipment'])
            ->whereNotNull('next_maintenance_date')
            ->whereBetween('next_maintenance_date', [$today, $nextMonth])
            ->get();
            
        foreach($upcomingMaintenance as $equipment) {
            $plannedItems->push([
                'id' => 'maintenance_' . $equipment->id,
                'ekipman' => $equipment->equipment->name ?? 'Bilinmiyor',
                'islem' => 'Planlı Bakım',
                'planlanan_tarih' => Carbon::parse($equipment->next_maintenance_date)->format('d.m.Y'),
                'sorumlu' => 'Teknik Ekip',
                'priority' => 'Orta',
                'status' => 'Planlandı',
                'type' => 'maintenance'
            ]);
        }
        
        return $plannedItems->sortBy('planlanan_tarih');
    }
    
    /**
     * İstatistikleri hesapla
     */
    private function getStatistics()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'toplam_arizali' => EquipmentStock::whereIn('status', ['Arızalı', 'arızalı'])->count(),
            'toplam_bakim' => EquipmentStock::whereIn('status', ['Bakım Gerekiyor', 'bakım'])->count(),
            'acil_durum' => Fault::where('priority', 'Yüksek')
                                 ->whereNotIn('status', ['Çözüldü', 'İptal Edildi'])
                                 ->count(),
            'bu_ay_cozulen' => Fault::where('status', 'Çözüldü')
                                   ->where('resolved_date', '>=', $thisMonth)
                                   ->count(),
            'bekleyen_islem' => Fault::whereIn('status', ['Beklemede', 'İşlemde'])->count(),
            'yaklasan_bakim' => EquipmentStock::whereNotNull('next_maintenance_date')
                                            ->whereBetween('next_maintenance_date', [$today, $today->copy()->addDays(30)])
                                            ->count()
        ];
    }
    
    /**
     * İşlem tipini metne çevir
     */
    private function getIslemText($type, $status)
    {
        $typeText = '';
        switch($type) {
            case 'arıza':
                $typeText = 'Arıza Giderme';
                break;
            case 'bakım':
                $typeText = 'Bakım İşlemi';
                break;
            default:
                $typeText = $type ?? 'Bilinmiyor';
        }
        
        return $typeText . ' (' . $status . ')';
    }
    
    /**
     * Aciliyet seviyesini hesapla
     */
    private function getAciliyetLevel($fault)
    {
        $reportedDate = Carbon::parse($fault->reported_date);
        $today = Carbon::today();
        $daysPassed = $reportedDate->diffInDays($today);
        
        if ($fault->priority === 'Yüksek' || $daysPassed > 7) {
            return 'critical';
        } elseif ($fault->priority === 'Orta' || $daysPassed > 3) {
            return 'warning';
        } else {
            return 'normal';
        }
    }
    
    /**
     * AJAX ile detay bilgisi getir
     */
    public function getDetail(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');
        
        if ($type === 'fault') {
            $fault = Fault::with(['equipmentStock.equipment', 'reporter', 'resolver'])->find($id);
            if (!$fault) {
                return response()->json(['error' => 'Kayıt bulunamadı'], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'ekipman' => $fault->equipmentStock->equipment->name ?? 'Bilinmiyor',
                    'tip' => $fault->type,
                    'oncelik' => $fault->priority,
                    'durum' => $fault->status,
                    'aciklama' => $fault->description,
                    'bildirim_tarihi' => $fault->reported_date ? Carbon::parse($fault->reported_date)->format('d.m.Y H:i') : '-',
                    'bildiren' => $fault->reporter->name ?? 'Bilinmiyor',
                    'sorumlu' => $fault->resolver->name ?? 'Atanmamış',
                    'cozum_tarihi' => $fault->resolved_date ? Carbon::parse($fault->resolved_date)->format('d.m.Y H:i') : '-',
                    'cozum_notu' => $fault->resolution_note ?? '-'
                ]
            ]);
        }
        
        return response()->json(['error' => 'Geçersiz tip'], 400);
    }
    
    /**
     * Durum güncelle
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required',
            'status' => 'required|string'
        ]);
        
        if ($request->type === 'fault') {
            $fault = Fault::find($request->id);
            if (!$fault) {
                return response()->json(['error' => 'Kayıt bulunamadı'], 404);
            }
            
            $fault->status = $request->status;
            if ($request->status === 'Çözüldü') {
                $fault->resolved_date = now();
                $fault->resolved_by = auth()->id();
            }
            $fault->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Durum başarıyla güncellendi'
            ]);
        }
        
        return response()->json(['error' => 'Geçersiz tip'], 400);
    }
}
