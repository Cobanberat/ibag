<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentStock;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Ekipmanlar';
        
        // Ekipman stoklarını, ilgili ekipman bilgisiyle birlikte sayfalayarak çekiyoruz
        // Individual tracking kontrolü: Ayrı takip özelliği olan ekipmanlar için her kayıt ayrı gösterilir
        $equipmentStocks = EquipmentStock::with(['equipment.category'])
            ->orderBy('id', 'asc')
            ->paginate(15)
            ->withQueryString();

        // Kategorileri çek
        $categories = \App\Models\EquipmentCategory::orderBy('name')->get();
        
        // Ekipman listesini çek (tekrar olmadan)
        $equipmentList = Equipment::select('id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get();

        return view('admin.equipment.index', compact('equipmentStocks', 'pageTitle', 'categories', 'equipmentList'));
    }

    /**
     * Get equipment data for AJAX requests
     */
    public function getEquipmentData(Request $request)
    {
        $query = EquipmentStock::with(['equipment.category']);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('equipment', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('code', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('model', 'like', "%{$search}%");
        }

        // Category filter
        if ($request->has('category') && $request->category !== '') {
            $query->whereHas('equipment.category', function($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        // Equipment filter
        if ($request->has('equipment') && !empty($request->equipment)) {
            $query->whereHas('equipment', function($q) use ($request) {
                $q->where('id', $request->equipment);
            });
        }

        // Individual tracking filter
        if ($request->has('tracking') && $request->tracking !== '') {
            $query->whereHas('equipment', function($q) use ($request) {
                $q->where('individual_tracking', $request->tracking);
            });
        }

        $equipmentStocks = $query->orderBy('id', 'asc')->paginate(15)->withQueryString();

        return response()->json([
            'data' => $equipmentStocks->items(),
            'pagination' => [
                'current_page' => $equipmentStocks->currentPage(),
                'last_page' => $equipmentStocks->lastPage(),
                'per_page' => $equipmentStocks->perPage(),
                'total' => $equipmentStocks->total()
            ]
        ]);
    }

    /**
     * Update equipment stock
     */
    public function update(Request $request, $id)
    {
        $equipmentStock = EquipmentStock::with('equipment')->findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'feature' => 'nullable|string',
            'quantity' => 'nullable|integer|min:0',
            'status' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'note' => 'nullable|string'
        ]);

        // Individual tracking kontrolü
        if ($equipmentStock->equipment && $equipmentStock->equipment->individual_tracking) {
            // Ayrı takip özelliği olan ekipmanlarda quantity her zaman 1 olmalı
            $validated['quantity'] = 1;
        }

        $equipmentStock->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ekipman başarıyla güncellendi'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $equipmentStock = EquipmentStock::with(['equipment.category', 'equipment.images'])->findOrFail($id);
        
        // Ekipman resimlerini de ekle
        if ($equipmentStock->equipment) {
            $equipmentStock->equipment->load('images');
        }
        
        return response()->json([
            'success' => true,
            'data' => $equipmentStock
        ]);
    }

    /**
     * Delete equipment stock
     */
    public function destroy($id)
    {
        $equipmentStock = EquipmentStock::findOrFail($id);
        $equipmentStock->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ekipman başarıyla silindi'
        ]);
    }

    /**
     * Export equipment data to CSV
     */
    public function exportCsv()
    {
        $equipmentStocks = EquipmentStock::with(['equipment.category'])->get();
        
        $filename = 'equipment_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($equipmentStocks) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Sıra', 'Kod', 'Ürün Cinsi', 'Marka', 'Model', 'Beden', 
                'Özellik', 'Adet', 'Durum', 'Lokasyon', 'Tarih', 'Not'
            ]);

            foreach ($equipmentStocks as $index => $stock) {
                fputcsv($file, [
                    $index + 1,
                    $stock->code ?? '-',
                    $stock->equipment->name ?? '-',
                    $stock->brand ?? '-',
                    $stock->model ?? '-',
                    $stock->size ?? '-',
                    $stock->feature ?? '-',
                    $stock->quantity ?? 0,
                    $stock->status ?? '-',
                    $stock->location ?? '-',
                    $stock->created_at ? $stock->created_at->format('d.m.Y') : '-',
                    $stock->note ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 