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
        $equipmentStocks = EquipmentStock::with(['equipment.category'])
            ->orderBy('id', 'asc')
            ->paginate(15);

        return view('admin.equipment.index', compact('equipmentStocks', 'pageTitle'));
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

        // Type filter
        if ($request->has('type') && !empty($request->type)) {
            $query->whereHas('equipment', function($q) use ($request) {
                $q->where('name', $request->type);
            });
        }

        // Brand filter
        if ($request->has('brand') && !empty($request->brand)) {
            $query->where('brand', 'like', "%{$request->brand}%");
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $equipmentStocks = $query->orderBy('id', 'asc')->paginate(15);

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
        $equipmentStock = EquipmentStock::findOrFail($id);
        
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
        $equipmentStock = EquipmentStock::with(['equipment.category'])->findOrFail($id);
        
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