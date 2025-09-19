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

        // QR kodları oluştur
        foreach ($equipmentStocks as $stock) {
            if (!$stock->qr_code) {
                $stock->generateQrCode();
            }
        }

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
     * Update equipment stock field (inline editing)
     */
    public function updateField(Request $request, $id)
    {
        $equipmentStock = EquipmentStock::with('equipment')->findOrFail($id);
        
        $request->validate([
            'field' => 'required|string|in:code,brand,model,size,feature,quantity,note,equipment_name',
            'value' => 'nullable|string|max:1000'
        ]);

        $field = $request->field;
        $value = $request->value;

        try {
            // Individual tracking kontrolü
            if ($equipmentStock->equipment && $equipmentStock->equipment->individual_tracking && $field === 'quantity') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ayrı takip özelliği olan ekipmanlarda miktar değiştirilemez'
                ], 400);
            }

            // Alan tipine göre güncelleme
            switch($field) {
                case 'equipment_name':
                    // Ekipman adını güncelle
                    if ($equipmentStock->equipment) {
                        $equipmentStock->equipment->update(['name' => $value]);
                    }
                    break;
                    
                case 'quantity':
                    // Miktar güncelleme
                    $quantity = (int)$value;
                    if ($quantity < 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Miktar 0\'dan küçük olamaz'
                        ], 400);
                    }
                    $equipmentStock->update([$field => $quantity]);
                    break;
                    
                default:
                    // Diğer alanlar
                    $equipmentStock->update([$field => $value]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Alan başarıyla güncellendi'
            ]);

        } catch (\Exception $e) {
            \Log::error('Inline edit error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Güncelleme sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
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
     * Delete equipment and all its stocks
     */
    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        
        // İlişkili tüm stok kayıtlarını sil
        $equipment->stocks()->delete();
        
        // Ekipmanı sil
        $equipment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ekipman ve tüm stok kayıtları başarıyla silindi'
        ]);
    }

    /**
     * Delete single equipment stock
     */
    public function destroyStock($id)
    {
        $equipmentStock = EquipmentStock::with('equipment')->findOrFail($id);
        
        // Ekipman adını sakla
        $equipmentName = $equipmentStock->equipment->name ?? 'Ekipman';
        $stockCode = $equipmentStock->code ?? $equipmentStock->id;
        
        // Stok kaydını sil
        $equipmentStock->delete();

        return response()->json([
            'success' => true,
            'message' => "{$equipmentName} (Kod: {$stockCode}) başarıyla silindi"
        ]);
    }

    /**
     * Bulk delete equipment stocks
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:stock_depo,id'
        ]);

        $ids = $request->ids;
        $deletedCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            try {
                $equipmentStock = EquipmentStock::find($id);
                if ($equipmentStock) {
                    $equipmentStock->delete();
                    $deletedCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "ID {$id}: " . $e->getMessage();
            }
        }

        if ($deletedCount > 0) {
            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} adet ekipman stok kaydı başarıyla silindi.",
                'deleted_count' => $deletedCount,
                'errors' => $errors
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hiçbir kayıt silinemedi.',
                'errors' => $errors
            ], 400);
        }
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

    /**
     * Download QR code for equipment stock
     */
    public function downloadQR($id)
    {
        $equipmentStock = EquipmentStock::with('equipment')->findOrFail($id);
        
        // QR kod yoksa oluştur
        if (!$equipmentStock->qr_code) {
            $equipmentStock->generateQrCode();
        }
        
        try {
            // Base64 SVG'yi PNG'ye çevir
            $svgData = base64_decode($equipmentStock->qr_code);
            
            // Imagick varsa PNG'ye çevir
            if (extension_loaded('imagick')) {
                $image = new \Imagick();
                $image->setBackgroundColor(new \ImagickPixel('transparent'));
                $image->readImageBlob($svgData);
                $image->setImageFormat('png');
                $image->setImageCompressionQuality(100);
                
                $pngData = $image->getImageBlob();
            } else {
                // Imagick yoksa SVG'yi direkt döndür
                $pngData = $svgData;
            }
            
            $filename = 'QR_' . ($equipmentStock->equipment->name ?? 'Ekipman') . '_' . ($equipmentStock->code ?? $equipmentStock->id) . (extension_loaded('imagick') ? '.png' : '.svg');
            $contentType = extension_loaded('imagick') ? 'image/png' : 'image/svg+xml';
            
            return response($pngData, 200, [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => strlen($pngData)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('QR kod indirme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'QR kod oluşturulamadı: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display equipment status page (maintenance and faulty equipment only)
     */
    public function status()
    {
        $pageTitle = 'Ekipman Durumu';
        
        // Sadece bakım gerektiren ve arızalı ekipman stoklarını çek
        $maintenanceEquipment = EquipmentStock::with(['equipment.category'])
            ->whereIn('status', ['bakım', 'arızalı', 'maintenance', 'faulty'])
            ->get();
            
        // Kategorileri çek
        $categories = \App\Models\EquipmentCategory::orderBy('name')->get();
        
        // Sorumlu kişileri çek (users tablosundan)
        $responsibleUsers = \App\Models\User::where('is_admin', 1)
            ->orWhere('role', 'technician')
            ->orderBy('name')
            ->get();
            
        // Durum seçenekleri
        $statusOptions = [
            'bakım' => 'Bakım Gerekiyor',
            'arızalı' => 'Arızalı'
        ];

        return view('admin.equipment.Status', compact(
            'pageTitle', 
            'maintenanceEquipment', 
            'categories', 
            'responsibleUsers',
            'statusOptions'
        ));
    }
} 