<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentStock;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentImage;
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
        
        // Her ekipman için stok miktarını ve durumunu hesaplayalım
        $equipmentsWithStock = $equipments->map(function($equipment) {
            // Tek takip ekipmanlarda sadece aktif stokları say
            if ($equipment->individual_tracking) {
                $totalQuantity = $equipment->stocks()
                    ->whereIn('status', ['Aktif', 'aktif', 'Sıfır', 'sıfır', 'available', 'Available'])
                    ->count();
            } else {
                // Çoklu takip ekipmanlarda quantity toplamını al
                $totalQuantity = $equipment->stocks()->sum('quantity') ?? 0;
            }
            
            $criticalLevel = $equipment->critical_level ?? 3;
            
            // Stok durumu hesaplama
            $isLowStock = $totalQuantity <= $criticalLevel && $totalQuantity > 0;
            $isEmpty = $totalQuantity == 0;
            $isSufficient = $totalQuantity > $criticalLevel;
            
            // Progress bar yüzdesi
            $percentage = $totalQuantity > 0 ? min(100, ($totalQuantity / max(1, $criticalLevel)) * 100) : 0;
            
            // Durum badge'i
            if ($isEmpty) {
                $statusBadge = 'empty';
                $statusText = 'Tükendi';
                $rowClass = 'table-danger';
                $barClass = 'bg-danger';
            } elseif ($isLowStock) {
                $statusBadge = 'low';
                $statusText = 'Az Stok';
                $rowClass = 'table-warning';
                $barClass = 'bg-warning';
            } else {
                $statusBadge = 'sufficient';
                $statusText = 'Yeterli';
                $rowClass = 'table-success';
                $barClass = 'bg-success';
            }
            
            $equipment->total_quantity = $totalQuantity;
            $equipment->critical_level = $criticalLevel;
            $equipment->status_badge = $statusBadge;
            $equipment->status_text = $statusText;
            $equipment->row_class = $rowClass;
            $equipment->bar_class = $barClass;
            $equipment->percentage = $percentage;
            
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
            
            // Her ekipman için stok miktarını ve durumunu hesaplayalım
            $equipmentsWithStock = $equipments->map(function($equipment) {
                // Tek takip ekipmanlarda sadece aktif stokları say
                if ($equipment->individual_tracking) {
                    $totalQuantity = $equipment->stocks()
                        ->whereIn('status', ['Aktif', 'aktif', 'Sıfır', 'sıfır', 'available', 'Available'])
                        ->count();
                } else {
                    // Çoklu takip ekipmanlarda quantity toplamını al
                    $totalQuantity = $equipment->stocks()->sum('quantity') ?? 0;
                }
                
                $criticalLevel = $equipment->critical_level ?? 3;
                
                // Stok durumu hesaplama
                $isLowStock = $totalQuantity <= $criticalLevel && $totalQuantity > 0;
                $isEmpty = $totalQuantity == 0;
                $isSufficient = $totalQuantity > $criticalLevel;
                
                // Progress bar yüzdesi
                $percentage = $totalQuantity > 0 ? min(100, ($totalQuantity / max(1, $criticalLevel)) * 100) : 0;
                
                // Durum badge'i
                if ($isEmpty) {
                    $statusBadge = 'empty';
                    $statusText = 'Tükendi';
                    $rowClass = 'table-danger';
                    $barClass = 'bg-danger';
                } elseif ($isLowStock) {
                    $statusBadge = 'low';
                    $statusText = 'Az Stok';
                    $rowClass = 'table-warning';
                    $barClass = 'bg-warning';
                } else {
                    $statusBadge = 'sufficient';
                    $statusText = 'Yeterli';
                    $rowClass = 'table-success';
                    $barClass = 'bg-success';
                }
                
                $equipment->total_quantity = $totalQuantity;
                $equipment->critical_level = $criticalLevel;
                $equipment->status_badge = $statusBadge;
                $equipment->status_text = $statusText;
                $equipment->row_class = $rowClass;
                $equipment->bar_class = $barClass;
                $equipment->percentage = $percentage;
                
                return $equipment;
            });

            // Filtreleme işlemleri
            $filteredEquipments = $equipmentsWithStock;

            // Search filter
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $filteredEquipments = $filteredEquipments->filter(function($equipment) use ($search) {
                    return stripos($equipment->name, $search) !== false || 
                           stripos($equipment->code ?? '', $search) !== false;
                });
            }

            // Category filter
            if ($request->has('category') && $request->category !== '') {
                $filteredEquipments = $filteredEquipments->filter(function($equipment) use ($request) {
                    return $equipment->category_id == $request->category;
                });
            }

            // Status filter
            if ($request->has('status') && !empty($request->status)) {
                switch ($request->status) {
                    case 'sufficient':
                        $filteredEquipments = $filteredEquipments->filter(function($equipment) {
                            return $equipment->status_badge === 'sufficient';
                        });
                        break;
                    case 'low':
                        $filteredEquipments = $filteredEquipments->filter(function($equipment) {
                            return $equipment->status_badge === 'low';
                        });
                        break;
                    case 'empty':
                        $filteredEquipments = $filteredEquipments->filter(function($equipment) {
                            return $equipment->status_badge === 'empty';
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
        $categories = EquipmentCategory::orderBy('name')->get();
        return view('admin.stock.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Gelen veriyi logla
        \Log::info('Store method called', [
            'request_data' => $request->all(),
            'has_name' => $request->has('name'),
            'has_category_id' => $request->has('category_id'),
            'method' => $request->method(),
            'url' => $request->url(),
            'headers' => $request->headers->all(),
            'is_ajax' => $request->ajax()
        ]);
        
        // AJAX isteği kontrolü
        $isAjax = $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest';
        
        // Manuel ekipman oluşturma modu
        if ($request->has('name') && $request->has('category_id')) {
            try {
                $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:equipment_categories,id',
                'code' => 'nullable|string|max:255',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'quantity' => 'required|integer|min:1',
                'size' => 'nullable|string|max:255',
                'feature' => 'nullable|string',
                'unit_type' => 'required|in:adet,metre,kilogram,litre,paket,kutu,çift,takım',
                'critical_level' => 'nullable|numeric|min:0.01',
                'note' => 'nullable|string',
                'stock_status' => 'nullable|string|in:Aktif,Kullanımda,Yok,Sıfır',
                'location' => 'nullable|string|max:255',
                'individual_tracking' => 'nullable|boolean',
                'next_maintenance_date' => 'nullable|date'
            ]);

            // Individual tracking kontrolü - validation sonrası
            $individualTracking = filter_var($validated['individual_tracking'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $quantity = (int)($validated['quantity'] ?? 1);
            
            \Log::info('Validation passed', [
                'validated_data' => $validated,
                'individual_tracking' => $individualTracking,
                'quantity' => $quantity
            ]);
            
            if ($individualTracking && $quantity != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ayrı takip özelliği olan ekipmanlarda miktar her zaman 1 olmalıdır'
                ], 400);
            }

            // Önce yeni ekipman oluştur
            $equipment = Equipment::create([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'unit_type' => $validated['unit_type'],
                'critical_level' => $validated['critical_level'] ?? 3,
                'individual_tracking' => $individualTracking
            ]);
            
            \Log::info('Equipment created', [
                'equipment_id' => $equipment->id,
                'equipment_data' => $equipment->toArray()
            ]);

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

                    $createdStock = EquipmentStock::create([
                        'equipment_id' => $equipment->id,
                        'code' => $validated['code'] ?: $this->generateRandomCode(),
                        'brand' => $validated['brand'],
                        'model' => $validated['model'],
                        'quantity' => 1, // Her kayıt 1 adet
                        'size' => $validated['size'],
                        'feature' => $validated['feature'] ?? null,
                        'note' => $validated['note'],
                        'status' => $validated['stock_status'] ?? 'Aktif',
                        'location' => $validated['location'] ?? 'Depo',
                        'photo_path' => $photoPath,
                        'next_maintenance_date' => $validated['next_maintenance_date'] ?? null
                    ]);
                    $stocks[] = $createdStock;

                    // Ekipman resimleri tablosuna da kaydet
                    if ($photoPath) {
                        EquipmentImage::create([
                            'equipment_id' => $equipment->id,
                            'image' => $photoPath,
                        ]);
                    }
                }
            } else {
                // Toplu tracking: Tek kayıt olarak quantity = $quantity
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $photoName = time() . '_0_' . $photo->getClientOriginalName();
                    $photo->move(public_path('uploads/equipment'), $photoName);
                    $photoPath = 'uploads/equipment/' . $photoName;
                }

                $createdStock = EquipmentStock::create([
                    'equipment_id' => $equipment->id,
                    'code' => $validated['code'] ?: $this->generateRandomCode(),
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'quantity' => $quantity,
                    'size' => $validated['size'],
                    'feature' => $validated['feature'] ?? null,
                    'note' => $validated['note'],
                    'status' => $validated['stock_status'] ?? 'Aktif',
                    'location' => $validated['location'] ?? 'Depo',
                    'photo_path' => $photoPath,
                    'next_maintenance_date' => $validated['next_maintenance_date'] ?? null
                ]);

                // Ekipman resimleri tablosuna da kaydet
                if ($photoPath) {
                    EquipmentImage::create([
                        'equipment_id' => $equipment->id,
                        'image' => $photoPath,
                    ]);
                }
            }

            \Log::info('Redirecting with success message');
            
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Yeni ekipman ve stok başarıyla oluşturuldu'
                ]);
            } else {
                return redirect()->route('stock.create')->with('success', 'Yeni ekipman ve stok başarıyla oluşturuldu');
            }
            } catch (\Exception $e) {
                \Log::error('Store method error: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ekipman eklenirken hata oluştu: ' . $e->getMessage()
                    ], 500);
                } else {
                    return redirect()->route('stock.create')->with('error', 'Ekipman eklenirken hata oluştu: ' . $e->getMessage());
                }
            }
        }

        // Mevcut ekipmana stok ekleme modu
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'status' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'feature' => 'nullable|string',
            'size' => 'nullable|string|max:255',
            'note' => 'nullable|string'
        ]);

        // Mevcut ekipmana stok ekleme: tracking türüne göre davran
        $equipmentExisting = Equipment::findOrFail($validated['equipment_id']);
        if ($equipmentExisting->individual_tracking) {
            // Her ekipman için ayrı kayıt
            for ($i = 0; $i < $validated['quantity']; $i++) {
                EquipmentStock::create([
                    'equipment_id' => $validated['equipment_id'],
                    'code' => $this->generateRandomCode(),
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'quantity' => 1,
                    'status' => $validated['status'],
                    'location' => $validated['location'],
                    'feature' => $validated['feature'],
                    'size' => $validated['size'],
                    'note' => $validated['note']
                ]);
            }
        } else {
            // Toplu tracking: Tek kayıt olarak miktarı arttır veya oluştur
            $existingAggregate = EquipmentStock::where('equipment_id', $validated['equipment_id'])->first();
            if ($existingAggregate) {
                $existingAggregate->update([
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'quantity' => ($existingAggregate->quantity ?? 0) + (int)$validated['quantity'],
                    'status' => $validated['status'],
                    'location' => $validated['location'],
                    'feature' => $validated['feature'],
                    'size' => $validated['size'],
                    'note' => $validated['note']
                ]);
            } else {
                EquipmentStock::create([
                    'equipment_id' => $validated['equipment_id'],
                    'code' => $this->generateRandomCode(),
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'quantity' => (int)$validated['quantity'],
                    'status' => $validated['status'],
                    'location' => $validated['location'],
                    'feature' => $validated['feature'],
                    'size' => $validated['size'],
                    'note' => $validated['note']
                ]);
            }
        }

        if ($isAjax) {
            return response()->json([
                'success' => true,
                'message' => 'Stok başarıyla oluşturuldu'
            ]);
        } else {
            return redirect()->route('stock.create')->with('success', 'Stok başarıyla oluşturuldu');
        }
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
            'unit_type' => 'required|in:adet,metre,kilogram,litre,paket,kutu,çift,takım',
            'critical_level' => 'required|numeric|min:0.01',
            'note' => 'nullable|string'
        ]);

        $equipment->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'unit_type' => $validated['unit_type'],
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
                'amount' => 'nullable|integer|min:1',
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
                'unit_type' => 'nullable|in:adet,metre,kilogram,litre,paket,kutu,çift,takım',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            // Amount varsayılan değer
            $validated['amount'] = $validated['amount'] ?? 1;

            // Unit type güncelleme (eğer gönderildiyse)
            if (isset($validated['unit_type']) && !empty($validated['unit_type'])) {
                $equipment->update(['unit_type' => $validated['unit_type']]);
            }

        // Individual tracking kontrolü
        if ($equipment->individual_tracking && $validated['amount'] != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Ayrı takip özelliği olan ekipmanlarda miktar her zaman 1 olmalıdır'
            ], 400);
        }

        // Mevcut toplam stok miktarını hesapla
        if ($equipment->individual_tracking) {
            // Tek takip ekipmanlarda sadece aktif stokları say
            $currentTotal = $equipment->stocks()
                ->whereIn('status', ['Aktif', 'aktif', 'Sıfır', 'sıfır', 'available', 'Available'])
                ->count();
        } else {
            // Çoklu takip ekipmanlarda quantity toplamını al
            $currentTotal = $equipment->stocks()->sum('quantity');
        }

        if ($validated['type'] === 'out') {
            if ($equipment->individual_tracking) {
                // Ayrı takip: her zaman 1 adet düş
                $validated['amount'] = 1;
                if (!empty($validated['code'])) {
                    // Kod girildiyse geçerli olmalı
                    $stockExists = EquipmentStock::where('code', $validated['code'])
                        ->where('equipment_id', $equipment->id)
                        ->exists();
                    if (!$stockExists) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Bu ekipmana ait geçersiz stok kodu'
                        ], 400);
                    }
                }
            } else {
                // Toplu takip: miktar zorunlu
                if (!isset($validated['amount']) || (int)$validated['amount'] < 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok çıkışı için miktar girmelisiniz'
                    ], 400);
                }
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
            

            
            // Ayrı takipte kullanılacak opsiyonlar; toplu takipte kullanılmaz
            $useSameProperties = filter_var($validated['use_same_properties'] ?? true, FILTER_VALIDATE_BOOLEAN);
            $useSingleImage = filter_var($validated['use_single_image'] ?? true, FILTER_VALIDATE_BOOLEAN);
            
            // Mevcut ekipmanın özelliklerini al
            $existingStock = $equipment->stocks()->first();
            $baseData = [
                'note' => $validated['note'] ?? 'Stok girişi',
                'status' => $validated['status'] ?? 'aktif'
            ];
            
            if ($equipment->individual_tracking && $useSameProperties) {
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
            } else if ($equipment->individual_tracking) {
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
                        // Ekipman resimlerine de ekle
                        EquipmentImage::create([
                            'equipment_id' => $equipment->id,
                            'image' => 'uploads/equipment/' . $photoName,
                        ]);
                    } elseif ($useSingleImage && isset($photos[0])) {
                        $photo = $photos[0];
                        $photoName = time() . '_' . $i . '_' . $photo->getClientOriginalName();
                        $photo->move(public_path('uploads/equipment'), $photoName);
                        $stockData['photo'] = 'uploads/equipment/' . $photoName;
                        EquipmentImage::create([
                            'equipment_id' => $equipment->id,
                            'image' => 'uploads/equipment/' . $photoName,
                        ]);
                    }
                    
                    $equipment->stocks()->create($stockData);
                }
            } else {
                // Toplu tracking: Tek kayıt, quantity arttırılır/oluşturulur
                $existingAggregate = $equipment->stocks()->first();

                // Resim işlemi (tek kayıt için ilk fotoğraf alınır)
                $photoPath = null;
                if (isset($photos[0])) {
                    $photo = $photos[0];
                    $photoName = time() . '_0_' . $photo->getClientOriginalName();
                    $photo->move(public_path('uploads/equipment'), $photoName);
                    $photoPath = 'uploads/equipment/' . $photoName;
                    EquipmentImage::create([
                        'equipment_id' => $equipment->id,
                        'image' => $photoPath,
                    ]);
                }

                if ($existingAggregate) {
                    // Mevcut kaydı güncelle ve miktarı arttır
                    $updateData = $baseData;
                    $updateData['quantity'] = ($existingAggregate->quantity ?? 0) + $amount;
                    // Fotoğraf geldiyse güncelle
                    if ($photoPath) {
                        $updateData['photo'] = $photoPath;
                    }
                    $existingAggregate->update($updateData);
                } else {
                    // Yeni tek kayıt oluştur
                    $stockData = array_merge($baseData, [
                        'quantity' => $amount,
                        'code' => $this->generateRandomCode(),
                    ]);
                    if ($photoPath) {
                        $stockData['photo'] = $photoPath;
                    }
                    $equipment->stocks()->create($stockData);
                }
            }
        } else {
            // Stok çıkışı - individual tracking'e göre işlem yap
            if ($equipment->individual_tracking) {
                // Kod varsa o kaydı, yoksa herhangi bir kaydı sil (tek adet)
                if (!empty($validated['code'])) {
                    $stockToDelete = EquipmentStock::where('code', $validated['code'])
                        ->where('equipment_id', $equipment->id)
                        ->first();
                } else {
                    $stockToDelete = EquipmentStock::where('equipment_id', $equipment->id)->first();
                }

                if (!$stockToDelete) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Çıkış yapılacak stok bulunamadı'
                    ], 400);
                }

                $stockToDelete->delete();
            } else {
                // Toplu tracking: Tek kayıt üzerinde miktarı düş
                $aggregate = $equipment->stocks()->first();
                if (!$aggregate) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Çıkış yapılacak stok bulunamadı'
                    ], 400);
                }

                $newQuantity = max(0, ($aggregate->quantity ?? 0) - (int)$validated['amount']);
                $aggregate->update(['quantity' => $newQuantity]);
            }
        }

        // Güncellenmiş toplam miktarı hesapla
        if ($equipment->individual_tracking) {
            // Tek takip ekipmanlarda sadece aktif stokları say
            $newTotal = $equipment->stocks()
                ->whereIn('status', ['Aktif', 'aktif', 'Sıfır', 'sıfır', 'available', 'Available'])
                ->count();
        } else {
            // Çoklu takip ekipmanlarda quantity toplamını al
            $newTotal = $equipment->stocks()->sum('quantity');
        }

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
        $idsInput = $request->input('ids', []);
        
        // JSON string ise decode et
        if (is_string($idsInput)) {
            $ids = json_decode($idsInput, true);
        } else {
            $ids = $idsInput;
        }
        
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
        
        // Toplam stok miktarını hesapla (tek/çoklu takip ayrımı ile)
        $totalQuantity = 0;
        $equipments = Equipment::with('stocks')->get();
        
        foreach ($equipments as $equipment) {
            if ($equipment->individual_tracking) {
                // Tek takip ekipmanlarda sadece aktif stokları say
                $totalQuantity += $equipment->stocks()
                    ->whereIn('status', ['Aktif', 'aktif', 'Sıfır', 'sıfır', 'available', 'Available'])
                    ->count();
            } else {
                // Çoklu takip ekipmanlarda quantity toplamını al
                $totalQuantity += $equipment->stocks()->sum('quantity') ?? 0;
            }
        }
        
        // Az stok ve boş stok sayılarını hesapla
        $lowStockCount = 0;
        $emptyStockCount = 0;
        
        foreach ($equipments as $equipment) {
            if ($equipment->individual_tracking) {
                // Tek takip ekipmanlarda sadece aktif stokları say
                $stockCount = $equipment->stocks()
                    ->whereIn('status', ['Aktif', 'aktif', 'Sıfır', 'sıfır', 'available', 'Available'])
                    ->count();
            } else {
                // Çoklu takip ekipmanlarda quantity toplamını al
                $stockCount = $equipment->stocks()->sum('quantity') ?? 0;
            }
            
            $criticalLevel = $equipment->critical_level ?? 3;
            
            if ($stockCount == 0) {
                $emptyStockCount++;
            } elseif ($stockCount <= $criticalLevel) {
                $lowStockCount++;
            }
        }

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
