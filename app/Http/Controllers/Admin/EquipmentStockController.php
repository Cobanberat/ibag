<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentStock;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EquipmentStockController extends Controller
{
    /**
     * Generate random code for stock
     */
    private function generateRandomCode()
    {
        $prefix = 'EQ';
        $timestamp = time();
        $random = Str::upper(Str::random(3));
        return "{$prefix}-{$timestamp}-{$random}";
    }

    private function findMatchingStock($equipment, $brand, $model, $size, $feature)
    {
        return $equipment->stocks()
            ->where('brand', $brand)
            ->where('model', $model)
            ->where('size', $size)
            ->where('feature', $feature)
            ->first();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Stok Yönetimi';
        
        // Equipment tablosundan gruplandırılmış veri çekiyoruz
        $equipments = Equipment::with(['category'])->get();
        
        // Her ekipman için stok miktarını hesaplayalım
        $equipmentsWithStock = $equipments->map(function($equipment) {
            $totalQuantity = $equipment->stocks()->sum('quantity') ?? 0;
            $equipment->total_quantity = $totalQuantity;
            return $equipment;
        });

        // Sayfalama
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $total = $equipmentsWithStock->count();
        $stocks = $equipmentsWithStock->forPage($currentPage, $perPage)->values();

        // Sayfalama bilgileri
        $pagination = [
            'current_page' => (int)$currentPage,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage,
            'total' => $total
        ];

        // Kategorileri filtre için çekiyoruz ve ekipmanları da yüklüyoruz
        $categories = EquipmentCategory::with(['equipments' => function($query) {
            $query->orderBy('name', 'asc');
        }])->orderBy('name', 'asc')->get();

        return view('admin.stock.index', compact('stocks', 'categories', 'pageTitle', 'pagination'));
    }

    /**
     * Get stock data for AJAX requests
     */
    public function getStockData(Request $request)
    {
        try {
            // Önce tüm ekipmanları ve stok miktarlarını çekelim
            $equipments = Equipment::with(['category'])->get();
            
            // Her ekipman için stok miktarını hesaplayalım
            $equipmentsWithStock = $equipments->map(function($equipment) {
                $totalQuantity = $equipment->stocks()->sum('quantity') ?? 0;
                $equipment->total_quantity = $totalQuantity;
                return $equipment;
            });

            // Filtreleme işlemleri
            $filteredEquipments = $equipmentsWithStock;

            // Search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $filteredEquipments = $filteredEquipments->filter(function($equipment) use ($search) {
                    return stripos($equipment->name, $search) !== false || 
                           stripos($equipment->code ?? '', $search) !== false;
                });
            }

            // Category filter
            if ($request->has('category') && !empty($request->category)) {
                $filteredEquipments = $filteredEquipments->filter(function($equipment) use ($request) {
                    return $equipment->category_id == $request->category;
                });
            }

            // Status filter
            if ($request->has('status') && !empty($request->status)) {
                switch ($request->status) {
                    case 'sufficient':
                        $filteredEquipments = $filteredEquipments->filter(function($equipment) {
                            return $equipment->total_quantity > 10;
                        });
                        break;
                    case 'low':
                        $filteredEquipments = $filteredEquipments->filter(function($equipment) {
                            return $equipment->total_quantity <= 10 && $equipment->total_quantity > 0;
                        });
                        break;
                    case 'empty':
                        $filteredEquipments = $filteredEquipments->filter(function($equipment) {
                            return $equipment->total_quantity == 0;
                        });
                        break;
                }
            }

            // Sayfalama
            $perPage = 15;
            $currentPage = $request->get('page', 1);
            $total = $filteredEquipments->count();
            $items = $filteredEquipments->forPage($currentPage, $perPage)->values();

            return response()->json([
                'success' => true,
                'data' => $items,
                'pagination' => [
                    'current_page' => (int)$currentPage,
                    'last_page' => ceil($total / $perPage),
                    'per_page' => $perPage,
                    'total' => $total
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Stock data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Veri yüklenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Manuel ekipman oluşturma modu
        if ($request->has('name') && $request->has('category_id')) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:equipment_categories,id',
                'code' => 'required|string|max:255|unique:stock_depo,code',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'manual_quantity' => 'required|integer|min:1',
                'size' => 'nullable|string|max:255',
                'critical_level' => 'nullable|integer|min:1',
                'note' => 'nullable|string',
                'status' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'individual_tracking' => 'nullable|string|in:0,1'
            ]);

            // Önce yeni ekipman oluştur
            $equipment = Equipment::create([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'critical_level' => $validated['critical_level'] ?? 3,
                'individual_tracking' => filter_var($validated['individual_tracking'] ?? false, FILTER_VALIDATE_BOOLEAN)
            ]);

            // Individual tracking kontrolü
            $individualTracking = filter_var($validated['individual_tracking'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $quantity = $validated['manual_quantity'];

            if ($individualTracking) {
                // Her ürün için ayrı kayıt oluştur
                $stocks = [];
                for ($i = 0; $i < $quantity; $i++) {
                    // Resim işlemi
                    $photoPath = null;
                    if ($request->hasFile('photo')) {
                        $photo = $request->file('photo');
                        $photoName = time() . '_' . $i . '_' . $photo->getClientOriginalName();
                        $photo->move(public_path('uploads/equipment'), $photoName);
                        $photoPath = 'uploads/equipment/' . $photoName;
                    }

                    $stocks[] = EquipmentStock::create([
                        'equipment_id' => $equipment->id,
                        'code' => $this->generateRandomCode(),
                        'brand' => $validated['brand'],
                        'model' => $validated['model'],
                        'quantity' => 1, // Her kayıt 1 adet
                        'size' => $validated['size'],
                        'feature' => $validated['feature'] ?? null,
                        'note' => $validated['note'],
                        'status' => $validated['status'] ?? 'aktif',
                        'location' => $validated['location'] ?? 'Depo',
                        'photo' => $photoPath
                    ]);
                }
            } else {
                // Tek kayıt oluştur
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $photoName = time() . '_' . $photo->getClientOriginalName();
                    $photo->move(public_path('uploads/equipment'), $photoName);
                    $photoPath = 'uploads/equipment/' . $photoName;
                }

                $stocks = [EquipmentStock::create([
                    'equipment_id' => $equipment->id,
                    'code' => $validated['code'],
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'quantity' => $quantity,
                    'size' => $validated['size'],
                    'feature' => $validated['feature'] ?? null,
                    'note' => $validated['note'],
                    'status' => $validated['status'] ?? 'aktif',
                    'location' => $validated['location'] ?? 'Depo',
                    'photo' => $photoPath
                ])];
            }

            return response()->json([
                'success' => true,
                'message' => 'Yeni ekipman ve stok başarıyla oluşturuldu',
                'data' => [
                    'equipment' => $equipment,
                    'stocks' => $stocks
                ]
            ]);
        }

        // Mevcut ekipmana stok ekleme modu
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'code' => 'required|string|max:255|unique:stock_depo,code',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'status' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'feature' => 'nullable|string',
            'size' => 'nullable|string|max:255',
            'note' => 'nullable|string'
        ]);

        $stock = EquipmentStock::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Stok başarıyla oluşturuldu',
            'data' => $stock
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $stock = EquipmentStock::with(['equipment.category'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $stock
        ]);
    }

    /**
     * Get equipment info for individual tracking check
     */
    public function getEquipmentInfo($id)
    {
        $equipment = Equipment::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $equipment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:equipments,code,' . $id,
            'critical_level' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        $equipment->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'critical_level' => $validated['critical_level']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ekipman başarıyla güncellendi',
            'data' => $equipment
        ]);
    }

    /**
     * Validate stock code for specific equipment
     */
    public function validateCode(Request $request)
    {
        $code = $request->get('code');
        $equipmentId = $request->get('equipment_id');
        
        if (!$code || !$equipmentId) {
            return response()->json(['valid' => false]);
        }
        
        // O ekipmana ait stok kodunu kontrol et
        $exists = EquipmentStock::where('code', $code)
            ->where('equipment_id', $equipmentId)
            ->exists();
        
        return response()->json(['valid' => $exists]);
    }

    /**
     * Validate reference stock code and return its properties
     */
    public function validateReferenceCode(Request $request)
    {
        $code = $request->get('code');
        $equipmentId = $request->get('equipment_id');
        
        if (!$code || !$equipmentId) {
            return response()->json(['valid' => false, 'data' => null]);
        }
        
        // O ekipmana ait stok kodunu bul ve özelliklerini döndür
        $stock = EquipmentStock::where('code', $code)
            ->where('equipment_id', $equipmentId)
            ->first();
        
        if ($stock) {
            return response()->json([
                'valid' => true,
                'data' => [
                    'brand' => $stock->brand,
                    'model' => $stock->model,
                    'size' => $stock->size,
                    'feature' => $stock->feature
                ]
            ]);
        }
        
        return response()->json(['valid' => false, 'data' => null]);
    }

    /**
     * Stock in/out operations
     */
    public function stockOperation(Request $request, $id)
    {
        try {
            $equipment = Equipment::findOrFail($id);
            
            $validated = $request->validate([
                'type' => 'required|in:in,out',
                'amount' => 'required|integer|min:1',
                'note' => 'nullable|string',
                'code' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
                'use_same_properties' => 'nullable|string|in:0,1',
                'use_single_image' => 'nullable|string|in:0,1',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'size' => 'nullable|string|max:255',
                'feature' => 'nullable|string',
                'reference_code' => 'nullable|string|max:255',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

        // Mevcut toplam stok miktarını hesapla
        $currentTotal = $equipment->stocks()->sum('quantity');

        if ($validated['type'] === 'out') {
            // Stok çıkışında kod kontrolü
            if (!isset($validated['code']) || empty($validated['code'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok çıkışı için kod girmelisiniz'
                ], 400);
            }
            
            // O ekipmana ait stok kodunu kontrol et
            $stockExists = EquipmentStock::where('code', $validated['code'])
                ->where('equipment_id', $equipment->id)
                ->exists();
            if (!$stockExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu ekipmana ait geçersiz stok kodu'
                ], 400);
            }
            
            if ($currentTotal < $validated['amount']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yetersiz stok! Mevcut stok: ' . $currentTotal
                ], 400);
            }
        }

        // Yeni bir EquipmentStock kaydı oluştur veya mevcut olanı güncelle
        if ($validated['type'] === 'in') {
            // Stok girişi - individual tracking'e göre işlem yap
            $amount = $validated['amount'];
            $useSameProperties = filter_var($validated['use_same_properties'] ?? true, FILTER_VALIDATE_BOOLEAN);
            $useSingleImage = filter_var($validated['use_single_image'] ?? true, FILTER_VALIDATE_BOOLEAN);
            
            // Mevcut ekipmanın özelliklerini al
            $existingStock = $equipment->stocks()->first();
            $baseData = [
                'note' => $validated['note'] ?? 'Stok girişi',
                'status' => $validated['status'] ?? 'aktif'
            ];
            
            if ($useSameProperties) {
                // Referans kodu varsa onun özelliklerini al
                if (isset($validated['reference_code']) && !empty($validated['reference_code'])) {
                    $referenceStock = EquipmentStock::where('code', $validated['reference_code'])
                        ->where('equipment_id', $equipment->id)
                        ->first();
                    
                    if ($referenceStock) {
                        $baseData['brand'] = $referenceStock->brand;
                        $baseData['model'] = $referenceStock->model;
                        $baseData['size'] = $referenceStock->size;
                        $baseData['feature'] = $referenceStock->feature;
                    } else {
                        // Referans kodu bulunamadı, mevcut stoktan al
                        if ($existingStock) {
                            $baseData['brand'] = $existingStock->brand;
                            $baseData['model'] = $existingStock->model;
                            $baseData['size'] = $existingStock->size;
                            $baseData['feature'] = $existingStock->feature;
                        }
                    }
                } else {
                    // Referans kodu yok, mevcut stoktan al
                    if ($existingStock) {
                        $baseData['brand'] = $existingStock->brand;
                        $baseData['model'] = $existingStock->model;
                        $baseData['size'] = $existingStock->size;
                        $baseData['feature'] = $existingStock->feature;
                    }
                }
            } else {
                // Manuel özellikler
                if (isset($validated['brand'])) $baseData['brand'] = $validated['brand'];
                if (isset($validated['model'])) $baseData['model'] = $validated['model'];
                if (isset($validated['size'])) $baseData['size'] = $validated['size'];
                if (isset($validated['feature'])) $baseData['feature'] = $validated['feature'];
            }
            
            // Resim dosyalarını al
            $photos = [];
            if ($request->hasFile('photos')) {
                $photos = $request->file('photos');
            }
            
            if ($equipment->individual_tracking) {
                // Individual tracking: Her ürün için ayrı kayıt
                for ($i = 0; $i < $amount; $i++) {
                    $stockData = array_merge($baseData, [
                        'quantity' => 1,
                        'code' => $this->generateRandomCode()
                    ]);
                    
                    // Resim işlemi
                    if (!$useSingleImage && isset($photos[$i])) {
                        $photo = $photos[$i];
                        $photoName = time() . '_' . $i . '_' . $photo->getClientOriginalName();
                        $photo->move(public_path('uploads/equipment'), $photoName);
                        $stockData['photo'] = 'uploads/equipment/' . $photoName;
                    } elseif ($useSingleImage && isset($photos[0])) {
                        $photo = $photos[0];
                        $photoName = time() . '_' . $i . '_' . $photo->getClientOriginalName();
                        $photo->move(public_path('uploads/equipment'), $photoName);
                        $stockData['photo'] = 'uploads/equipment/' . $photoName;
                    }
                    
                    $equipment->stocks()->create($stockData);
                }
            } else {
                // Toplu tracking: Aynı özellikleri kontrol et
                $brand = $baseData['brand'] ?? null;
                $model = $baseData['model'] ?? null;
                $size = $baseData['size'] ?? null;
                $feature = $baseData['feature'] ?? null;
                
                // Aynı özellikleri arayan stok kaydı var mı?
                $matchingStock = $this->findMatchingStock($equipment, $brand, $model, $size, $feature);
                
                if ($matchingStock) {
                    // Aynı özelliklerde stok var, miktarını artır
                    $matchingStock->quantity += $amount;
                    $matchingStock->save();
                } else {
                    // Aynı özelliklerde stok yok, yeni kayıt oluştur
                    $stockData = array_merge($baseData, [
                        'quantity' => $amount,
                        'code' => $this->generateRandomCode()
                    ]);
                    
                    // Resim işlemi
                    if (isset($photos[0])) {
                        $photo = $photos[0];
                        $photoName = time() . '_' . $photo->getClientOriginalName();
                        $photo->move(public_path('uploads/equipment'), $photoName);
                        $stockData['photo'] = 'uploads/equipment/' . $photoName;
                    }
                    
                    $equipment->stocks()->create($stockData);
                }
            }
        } else {
            // Stok çıkışı - individual tracking'e göre işlem yap
            if ($equipment->individual_tracking) {
                // Individual tracking: Girilen koda sahip kaydı sil
                $stockToDelete = EquipmentStock::where('code', $validated['code'])
                    ->where('equipment_id', $equipment->id)
                    ->first();
                
                if (!$stockToDelete) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bu stok kodu bulunamadı'
                    ], 400);
                }
                
                // Stok kaydını sil
                $stockToDelete->delete();
            } else {
                // Toplu tracking: Miktar düşür
                $existingStock = $equipment->stocks()->first();
                if (!$existingStock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bu ekipman için stok kaydı bulunamadı'
                    ], 400);
                }
                
                if ($existingStock->quantity < $validated['amount']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Yetersiz stok! Mevcut stok: ' . $existingStock->quantity
                    ], 400);
                }
                
                $existingStock->quantity -= $validated['amount'];
                $existingStock->save();
            }
        }

        // Güncellenmiş toplam miktarı hesapla
        $newTotal = $equipment->stocks()->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Stok ' . ($validated['type'] === 'in' ? 'girişi' : 'çıkışı') . ' başarıyla yapıldı',
            'data' => [
                'equipment_id' => $equipment->id,
                'total_quantity' => $newTotal
            ]
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $errorMessages = [];
            foreach ($errors as $field => $messages) {
                $errorMessages[] = implode(', ', $messages);
            }
            return response()->json([
                'success' => false,
                'message' => 'Validation hatası: ' . implode('; ', $errorMessages)
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Stock operation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Stok işlemi sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        
        // Önce bu equipment'a ait tüm stokları sil
        $equipment->stocks()->delete();
        
        // Sonra equipment'ı sil
        $equipment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ekipman ve tüm stokları başarıyla silindi'
        ]);
    }

    /**
     * Bulk delete stocks
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Silinecek ekipman seçilmedi'
            ], 400);
        }

        $deletedCount = 0;
        foreach ($ids as $id) {
            $equipment = Equipment::find($id);
            if ($equipment) {
                // Önce bu equipment'a ait tüm stokları sil
                $equipment->stocks()->delete();
                // Sonra equipment'ı sil
                $equipment->delete();
                $deletedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} ekipman ve tüm stokları başarıyla silindi"
        ]);
    }

    /**
     * Get stock statistics
     */
    public function getStatistics()
    {
        $totalEquipments = Equipment::count();
        $totalQuantity = EquipmentStock::sum('quantity');
        $lowStockCount = Equipment::withCount(['stocks as total_quantity' => function($query) {
            $query->select(\DB::raw('SUM(quantity)'));
        }])->having('total_quantity', '<=', \DB::raw('critical_level'))
          ->having('total_quantity', '>', 0)->count();
        $emptyStockCount = Equipment::withCount(['stocks as total_quantity' => function($query) {
            $query->select(\DB::raw('SUM(quantity)'));
        }])->having('total_quantity', 0)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_equipments' => $totalEquipments,
                'total_quantity' => $totalQuantity,
                'low_stock_count' => $lowStockCount,
                'empty_stock_count' => $emptyStockCount
            ]
        ]);
    }
}
