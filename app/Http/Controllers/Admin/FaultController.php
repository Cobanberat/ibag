<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fault;
use App\Models\EquipmentStock;
use App\Models\EquipmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FaultController extends Controller
{
    public function index()
    {
        $pageTitle = 'Arıza Bildirimi';
        
        // Sadece tek takip ekipmanlarını getir (individual_tracking = true)
        // Aynı takip numarasına sahip ekipmanları filtrele (sadece ilkini al)
        $uniqueCodeIds = EquipmentStock::selectRaw('code, MIN(id) as min_id')
            ->whereNotNull('code')
            ->where('code', '!=', '')
            ->whereNotIn('status', ['Arızalı', 'Bakım Gerekiyor', 'Kullanımda'])
            ->whereHas('equipment', function($query) {
                $query->where('individual_tracking', true);
            })
            ->groupBy('code')
            ->pluck('min_id');
        
        // Sadece bu ID'lere sahip ekipmanları getir
        $equipmentStocks = EquipmentStock::with(['equipment.category'])
            ->whereIn('id', $uniqueCodeIds)
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $categories = EquipmentCategory::orderBy('name')->get();
        
        return view('admin.fault.index', compact('pageTitle', 'equipmentStocks', 'categories'));
    }

    public function status()
    {
        $pageTitle = 'Arıza Yönetimi';
        
        // Bekleyen arızalar
        $faults = Fault::with(['equipmentStock.equipment.category', 'reporter'])
            ->whereIn('status', ['Beklemede', 'İşlemde'])
            ->orderBy('priority', 'desc')
            ->orderBy('reported_date', 'desc')
            ->get();
            
        // Bakım gereken ekipmanlar - faults tablosundan
        $maintenanceItems = Fault::with(['equipmentStock.equipment.category'])
            ->where('type', 'bakım')
            ->whereIn('status', ['Beklemede', 'İşlemde'])
            ->orderBy('reported_date', 'desc')
            ->get();
            
        // Arızalı ekipmanlar - faults tablosundan
        $faultyItems = Fault::with(['equipmentStock.equipment.category'])
            ->where('type', 'arıza')
            ->whereIn('status', ['Beklemede', 'İşlemde'])
            ->orderBy('reported_date', 'desc')
            ->get();
            
        // Çözülen arızalar
        $resolvedFaults = Fault::with(['equipmentStock.equipment.category', 'reporter', 'resolver'])
            ->where('status', 'Çözüldü')
            ->orderBy('resolved_date', 'desc')
            ->get();
            
        // İstatistikler
        $stats = [
            'bekleyen' => Fault::whereIn('status', ['Beklemede', 'İşlemde'])->count(),
            'bakim' => $maintenanceItems->count(),
            'arizali' => $faultyItems->count(),
            'cozulen' => $resolvedFaults->count()
        ];
        
        $categories = EquipmentCategory::orderBy('name')->get();
        
        return view('admin.fault.Status', compact(
            'pageTitle', 
            'faults', 
            'maintenanceItems', 
            'faultyItems', 
            'resolvedFaults', 
            'stats',
            'categories'
        ));
    }

    public function show($id)
    {
        $fault = Fault::with(['equipmentStock.equipment.category', 'reporter'])
            ->findOrFail($id);
            
        return response()->json([
            'success' => true,
            'fault' => [
                'equipment_name' => $fault->equipmentStock->equipment->name ?? 'Bilinmeyen',
                'equipment_code' => $fault->equipmentStock->equipment->code ?? 'Kod yok',
                'category_name' => $fault->equipmentStock->equipment->category->name ?? 'Kategori yok',
                'type' => $fault->type,
                'priority' => $fault->priority,
                'status' => $fault->status,
                'description' => $fault->description,
                'reported_date' => $fault->reported_date ? \Carbon\Carbon::parse($fault->reported_date)->format('d.m.Y H:i') : 'Tarih yok',
                'reporter_name' => $fault->reporter->name ?? 'Bilinmeyen',
                'photo_path' => $fault->photo_path
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipment_stock_id' => 'required|exists:stock_depo,id',
            'type' => 'required|in:arıza,bakım,diğer',
            'priority' => 'required|in:normal,yüksek,acil',
            'description' => 'required|string|min:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'reported_date' => 'required|date'
        ]);

        try {
            $fault = new Fault();
            $fault->equipment_stock_id = $request->equipment_stock_id;
            $fault->reported_by = Auth::id();
            $fault->type = $request->type;
            $fault->priority = $request->priority;
            $fault->description = $request->description;
            $fault->reported_date = $request->reported_date;
            $fault->status = 'Beklemede';

            // Fotoğraf yükleme
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('faults', 'public');
                $fault->photo_path = $photoPath;
            }

            $fault->save();

            // EquipmentStock durumunu güncelle
            $equipmentStock = EquipmentStock::find($request->equipment_stock_id);
            if ($equipmentStock) {
                if ($request->type === 'arıza') {
                    $equipmentStock->status = 'Arızalı';
                } elseif ($request->type === 'bakım') {
                    $equipmentStock->status = 'Bakım Gerekiyor';
                }
                $equipmentStock->save();
            }

            $message = $request->type === 'bakım' ? 'Bakım bildirimi başarıyla oluşturuldu.' : 'Arıza bildirimi başarıyla oluşturuldu.';
            return redirect()->route('admin.fault.status')->with('success', $message);
        } catch (\Exception $e) {
            $logMessage = $request->type === 'bakım' ? 'Bakım bildirimi oluşturulurken hata: ' : 'Arıza bildirimi oluşturulurken hata: ';
            $errorMessage = $request->type === 'bakım' ? 'Bakım bildirimi oluşturulurken hata oluştu.' : 'Arıza bildirimi oluşturulurken hata oluştu.';
            
            // Detaylı hata logu
            Log::error($logMessage . $e->getMessage());
            Log::error('Request data: ' . json_encode($request->all()));
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Geliştirme ortamında detaylı hata mesajı göster
            if (config('app.debug')) {
                $errorMessage .= ' Hata detayı: ' . $e->getMessage();
            }
            
            return back()->with('error', $errorMessage);
        }
    }

    public function resolve(Request $request)
    {
        $request->validate([
            'equipment_stock_id' => 'required|exists:stock_depo,id',
            'type' => 'required|in:arıza,bakım,diğer',
            'resolution_note' => 'required|string|min:10',
            'resolved_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'resolution_cost' => 'nullable|numeric|min:0',
            'resolution_time' => 'nullable|numeric|min:0',
            'next_maintenance_date' => 'nullable|date'
        ]);

        try {
            $equipmentStock = EquipmentStock::findOrFail($request->equipment_stock_id);
            
            // Ekipman durumunu güncelle
            $equipmentStock->status = 'Aktif';
            $equipmentStock->save();
            
            // Fault kaydını güncelle
            $fault = Fault::where('equipment_stock_id', $request->equipment_stock_id)
                ->whereIn('status', ['Beklemede', 'İşlemde'])
                ->first();
                
            if ($fault) {
                $fault->status = 'Çözüldü';
                $fault->resolution_note = $request->resolution_note;
                $fault->resolved_date = now();
                $fault->resolved_by = Auth::id();
                $fault->resolution_cost = $request->resolution_cost;
                $fault->resolution_time = $request->resolution_time;
                
                // Çözüm fotoğrafı yükleme
                if ($request->hasFile('resolved_photo')) {
                    $photoPath = $request->file('resolved_photo')->store('faults/resolved', 'public');
                    $fault->resolved_photo_path = $photoPath;
                }
                
                $fault->save();
            }
            
            // Sonraki bakım tarihini sadece bakım tipinde kaydet
            if ($request->type === 'bakım' && $request->next_maintenance_date) {
                $equipmentStock->next_maintenance_date = $request->next_maintenance_date;
                $equipmentStock->save();
            }

            return response()->json([
                'success' => true, 
                'message' => ucfirst($request->type) . ' başarıyla çözüldü.'
            ]);
        } catch (\Exception $e) {
            Log::error('Çözüm kaydedilirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Çözüm kaydedilirken hata oluştu: ' . $e->getMessage()
            ]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Beklemede,İşlemde,Çözüldü,İptal Edildi',
            'status_note' => 'nullable|string'
        ]);

        try {
            $fault = Fault::findOrFail($id);
            $fault->status = $request->status;
            
            if ($request->status_note) {
                $fault->status_note = $request->status_note;
            }
            
            if ($request->status === 'Çözüldü') {
                $fault->resolved_date = now();
                $fault->resolved_by = Auth::id();
            }
            
            $fault->save();

            return response()->json([
                'success' => true, 
                'message' => 'Durum başarıyla güncellendi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Durum güncellenirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Durum güncellenirken hata oluştu: ' . $e->getMessage()
            ]);
        }
    }

    public function getResolvedFault($id)
    {
        $fault = Fault::with(['equipmentStock.equipment.category', 'resolver'])
            ->where('id', $id)
            ->where('status', 'Çözüldü')
            ->firstOrFail();
            
        return response()->json([
            'success' => true,
            'fault' => [
                'equipment_name' => $fault->equipmentStock->equipment->name ?? 'Bilinmeyen',
                'equipment_code' => $fault->equipmentStock->equipment->code ?? 'Kod yok',
                'category_name' => $fault->equipmentStock->equipment->category->name ?? 'Kategori yok',
                'resolved_date' => $fault->resolved_date ? \Carbon\Carbon::parse($fault->resolved_date)->format('d.m.Y H:i') : 'Tarih yok',
                'resolver_name' => $fault->resolver->name ?? 'Bilinmeyen',
                'resolution_cost' => $fault->resolution_cost,
                'resolution_time' => $fault->resolution_time,
                'resolution_note' => $fault->resolution_note,
                'resolved_photo_path' => $fault->resolved_photo_path
            ]
        ]);
    }
}
