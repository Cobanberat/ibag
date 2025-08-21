<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fault;
use App\Models\EquipmentStock;
use App\Models\EquipmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FaultController extends Controller
{
    public function index()
    {
        $pageTitle = 'Arıza Bildirimi';
        
        // Sadece kullanılabilir ekipmanları getir (arızalı veya bakım gerektiren olmayanlar)
        $equipmentStocks = EquipmentStock::with(['equipment.category'])
            ->whereNotIn('status', ['Arızalı', 'Bakım Gerekiyor', 'Kullanımda'])
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $categories = EquipmentCategory::orderBy('name')->get();
        
        return view('admin.fault.index', compact('pageTitle', 'equipmentStocks', 'categories'));
    }

    public function status()
    {
        $pageTitle = 'Arıza Durumu';
        
        // Aktif arızalar
        $activeFaults = Fault::with(['equipmentStock.equipment.category', 'reporter'])
            ->active()
            ->orderBy('priority', 'desc')
            ->orderBy('reported_date', 'desc')
            ->get();
            
        // Giderilmiş arızalar
        $resolvedFaults = Fault::with(['equipmentStock.equipment.category', 'reporter', 'resolver'])
            ->resolved()
            ->orderBy('resolved_date', 'desc')
            ->get();
            
        // İstatistikler
        $stats = [
            'beklemede' => Fault::where('status', 'beklemede')->count(),
            'islemde' => Fault::where('status', 'işlemde')->count(),
            'giderildi' => Fault::resolved()->count(),
            'toplam' => Fault::count()
        ];
        
        return view('admin.fault.Status', compact('pageTitle', 'activeFaults', 'resolvedFaults', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipment_stock_id' => 'required|exists:equipment_stock,id',
            'type' => 'required|in:arıza,bakım,diğer',
            'priority' => 'required|in:normal,yüksek,acil',
            'description' => 'required|string|min:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'reported_date' => 'required|date'
        ]);

        $fault = new Fault();
        $fault->equipment_stock_id = $request->equipment_stock_id;
        $fault->reported_by = Auth::id();
        $fault->type = $request->type;
        $fault->priority = $request->priority;
        $fault->description = $request->description;
        $fault->reported_date = $request->reported_date;
        $fault->status = 'beklemede';

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

        return redirect()->route('admin.fault.status')->with('success', 'Arıza bildirimi başarıyla oluşturuldu.');
    }

    public function resolve(Request $request, $id)
    {
        $request->validate([
            'resolution_note' => 'required|string|min:10',
            'resolved_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'resolved_date' => 'required|date'
        ]);

        $fault = Fault::findOrFail($id);
        $fault->status = 'giderildi';
        $fault->resolution_note = $request->resolution_note;
        $fault->resolved_date = $request->resolved_date;
        $fault->resolved_by = Auth::id();

        // Çözüm fotoğrafı yükleme
        if ($request->hasFile('resolved_photo')) {
            $photoPath = $request->file('resolved_photo')->store('faults/resolved', 'public');
            $fault->resolved_photo_path = $photoPath;
        }

        $fault->save();

        // EquipmentStock durumunu güncelle
        $equipmentStock = $fault->equipmentStock;
        if ($equipmentStock) {
            $equipmentStock->status = 'Aktif';
            $equipmentStock->save();
        }

        return redirect()->route('admin.fault.status')->with('success', 'Arıza başarıyla giderildi olarak işaretlendi.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:beklemede,işlemde,giderildi,iptal'
        ]);

        $fault = Fault::findOrFail($id);
        $fault->status = $request->status;
        $fault->save();

        return response()->json(['success' => true, 'message' => 'Durum güncellendi']);
    }
}
