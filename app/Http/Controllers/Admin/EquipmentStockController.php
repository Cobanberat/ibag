<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentStock;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
     * Generate QR code for equipment stock
     */
    private function generateQrCode($equipmentStock)
    {
        // QR kod oluşturma geçici olarak devre dışı
        return '';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Stok Yönetimi';
        
        // Kategorileri çek
        $categories = \App\Models\EquipmentCategory::orderBy('name')->get();
        
        // Ekipman listesini çek (tekrar olmadan)
        $equipmentList = Equipment::select('id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get();

        return view('admin.stock.index', compact('pageTitle', 'categories', 'equipmentList'));
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
            $query->whereHas('equipment', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Individual tracking filter
        if ($request->has('individual_tracking') && $request->individual_tracking !== '') {
            $query->whereHas('equipment', function($q) use ($request) {
                $q->where('individual_tracking', $request->individual_tracking);
            });
        }

        $equipmentStocks = $query->orderBy('id', 'asc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $equipmentStocks
        ]);
    }

    /**
     * Get stock data for AJAX requests (Filtreler için)
     */
    public function getStockData(Request $request)
    {
        // Debug için log ekle (geçici)
        // \Log::info('Stock data request:', [
        //     'search' => $request->search,
        //     'category' => $request->category,
        //     'tracking' => $request->tracking,
        //     'status' => $request->status
        // ]);
        
        $query = Equipment::with(['category'])
            ->selectRaw('
                equipments.id,
                equipments.name,
                equipments.category_id,
                equipments.unit_type,
                equipments.critical_level,
                equipments.individual_tracking,
                equipments.status,
                equipments.created_at,
                equipments.updated_at,
                COALESCE(SUM(stock_depo.quantity), 0) as total_quantity
            ')
            ->leftJoin('stock_depo', 'equipments.id', '=', 'stock_depo.equipment_id');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('equipments.name', 'like', "%{$search}%");
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('equipments.category_id', $request->category);
        }

        // Tracking filter
        if ($request->filled('tracking')) {
            $query->where('equipments.individual_tracking', $request->tracking);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('equipments.status', $request->status);
        }

        // Sayfa başına gösterilecek kayıt sayısını al
        $perPage = $request->get('per_page', 15);
        
        // Maksimum 1000 kayıt ile sınırla (performans için)
        if ($perPage > 1000) {
            $perPage = 1000;
        }
        
        $stocks = $query->groupBy('equipments.id', 'equipments.name', 'equipments.category_id', 'equipments.unit_type', 'equipments.critical_level', 'equipments.individual_tracking', 'equipments.status', 'equipments.created_at', 'equipments.updated_at')
            ->orderBy('equipments.name', 'asc')
            ->paginate($perPage);

        // Debug için sonuç sayısını logla (geçici)
        // \Log::info('Stock data result:', [
        //     'total' => $stocks->total(),
        //     'count' => count($stocks->items())
        // ]);

        // Her stok için accessor'ları hesapla
        $stocks->getCollection()->transform(function ($stock) {
            // Ekipman ismi ve kategori bilgilerini ekle
            $stock->name = $stock->name;
            $stock->category = $stock->category;
            
            // Individual tracking değerini boolean'a çevir
            $stock->individual_tracking = (bool) $stock->individual_tracking;
            
            // Equipment modelindeki accessor'ları kullan
            $stock->row_class = $stock->getRowClassAttribute();
            $stock->bar_class = $stock->getBarClassAttribute();
            $stock->percentage = $stock->getPercentageAttribute();
            $stock->status_badge = $stock->getStatusBadgeAttribute();
            $stock->stock_status = $stock->getStockStatusAttribute();
            $stock->unit_type_label = $stock->getUnitTypeLabelAttribute();
            $stock->critical_level = $stock->getCriticalLevelAttribute();
            
            return $stock;
        });

        return response()->json([
            'success' => true,
            'data' => $stocks->items(),
            'pagination' => [
                'current_page' => $stocks->currentPage(),
                'last_page' => $stocks->lastPage(),
                'per_page' => $stocks->perPage(),
                'total' => $stocks->total()
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Yeni Ekipman Ekle';
        $categories = EquipmentCategory::orderBy('name')->get();
        
        return view('admin.stock.create', compact('pageTitle', 'categories'));
    }

    /**
     * Normalize unit type value
     */
    private function normalizeUnitType($unitType)
    {
        if (empty($unitType)) {
            return 'adet';
        }
        
        $normalized = strtolower(trim($unitType));
        
        // Common misspellings and variations
        $mappings = [
            'adet' => 'adet',
            'adEt' => 'adet',
            'Adet' => 'adet',
            'ADET' => 'adet',
            'adet.' => 'adet',
            'adet,' => 'adet',
            'adet;' => 'adet',
            
            'metre' => 'metre',
            'metr' => 'metre',
            'Metre' => 'metre',
            'METRE' => 'metre',
            'm' => 'metre',
            'm.' => 'metre',
            
            'kilogram' => 'kilogram',
            'kg' => 'kilogram',
            'kilo' => 'kilogram',
            'Kilogram' => 'kilogram',
            'KILOGRAM' => 'kilogram',
            'kg.' => 'kilogram',
            
            'litre' => 'litre',
            'lt' => 'litre',
            'l' => 'litre',
            'Litre' => 'litre',
            'LİTRE' => 'litre',
            'lt.' => 'litre',
            
            'paket' => 'paket',
            'Paket' => 'paket',
            'PAKET' => 'paket',
            'pkt' => 'paket',
            'pkt.' => 'paket',
            
            'kutu' => 'kutu',
            'Kutu' => 'kutu',
            'KUTU' => 'kutu',
            'box' => 'kutu',
            
            'çift' => 'çift',
            'Çift' => 'çift',
            'ÇİFT' => 'çift',
            'pair' => 'çift',
            
            'takım' => 'takım',
            'Takım' => 'takım',
            'TAKIM' => 'takım',
            'set' => 'takım',
        ];
        
        return $mappings[$normalized] ?? $normalized;
    }
    
    /**
     * Normalize tracking type value
     */
    private function normalizeTrackingType($trackingType)
    {
        if (empty($trackingType)) {
            return false; // Default to toplu takip
        }
        
        $normalized = strtolower(trim($trackingType));
        
        // Common misspellings and variations for individual tracking
        $individualTrackingVariations = [
            'ayrı takip',
            'ayri takip',
            'ayrı',
            'ayri',
            'ayrı takıp',
            'ayri takıp',
            'ayrı takip.',
            'ayri takip.',
            'ayrı takip,',
            'ayri takip,',
            'ayrı takip;',
            'ayri takip;',
            'ayrı takip:',
            'ayri takip:',
            'individual',
            'tek',
            'single',
            '1',
            'evet',
            'yes',
            'true',
        ];
        
        return in_array($normalized, $individualTrackingVariations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Handle both quantity and manual_quantity fields
        $quantity = $request->quantity ?? $request->manual_quantity;
        
        // Hızlı ekleme modu (mevcut ekipman kullanımı)
        if ($request->equipment_id) {
            $equipment = Equipment::find($request->equipment_id);
            if (!$equipment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ekipman bulunamadı'
                ], 404);
            }
            
            $individualTracking = $equipment->individual_tracking;
        } else {
            // Manuel ekleme modu
            if (!$request->name || !$request->category_id || !$quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ekipman adı, kategori ve miktar gereklidir'
                ], 400);
            }
            
            // Convert string boolean values to actual booleans
            $individualTracking = $request->individual_tracking;
            if ($individualTracking === 'true' || $individualTracking === '1') {
                $individualTracking = true;
            } elseif ($individualTracking === 'false' || $individualTracking === '0') {
                $individualTracking = false;
            } else {
                $individualTracking = (bool) $individualTracking;
            }
        }

        // Ekipman oluştur (sadece manuel modda)
        if (!$request->equipment_id) {
            $equipment = Equipment::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'critical_level' => $request->critical_level ?: 3, // Default 3
                'unit_type' => $request->unit_type,
                'individual_tracking' => $individualTracking,
                'status' => Equipment::STATUS_ACTIVE
            ]);
        }

        // Fotoğraf işleme
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('equipment_photos', 'public');
        }

        // Kod oluştur
        $code = $request->code ?: $this->generateRandomCode();

        // Individual tracking kontrolü
        $quantity = $individualTracking ? 1 : $quantity;

        // Ekipman stoku oluştur
        $equipmentStock = EquipmentStock::create([
            'equipment_id' => $equipment->id,
            'brand' => $request->brand,
            'model' => $request->model,
            'size' => $request->size,
            'feature' => $request->feature,
            'status' => 'Aktif',
            'code' => $code,
            'qr_code' => $request->qr_code,
            'location' => $request->location,
            'quantity' => $quantity,
            'note' => $request->note,
            'next_maintenance_date' => $request->next_maintenance_date,
            'photo_path' => $photoPath
        ]);

        // Arızalı ve bakımda ekipmanları sadece stok tablosuna kaydet, faults tablosuna kaydetme

        // Eğer QR kod girilmemişse otomatik oluştur
        if (!$request->qr_code) {
            $qrCode = $this->generateQrCode($equipmentStock);
            $equipmentStock->update(['qr_code' => $qrCode]);
        }

        // Ekipman resmi varsa EquipmentImage tablosuna da ekle
        if ($photoPath) {
            EquipmentImage::create([
                'equipment_id' => $equipment->id,
                'path' => $photoPath,
                'is_primary' => true
            ]);
        }

        // Always return JSON
        return response()->json([
            'success' => true,
            'message' => 'Ekipman başarıyla eklendi!',
            'equipment_id' => $equipment->id
        ]);
    }

    /**
     * Repair equipment (fix fault)
     */
    public function repair($id)
    {
        try {
            $equipmentStock = EquipmentStock::findOrFail($id);
            
            // Ekipman durumunu aktif yap
            $equipmentStock->status = 'Aktif';
            $equipmentStock->save();
            
            // Aktif arıza kaydını çözüldü olarak işaretle
            $fault = \App\Models\Fault::where('equipment_stock_id', $id)
                ->where('type', 'arıza')
                ->whereIn('status', ['Beklemede', 'İşlemde'])
                ->first();
                
            if ($fault) {
                $fault->status = 'Çözüldü';
                $fault->resolved_date = now()->toDateString();
                $fault->resolved_by = auth()->id();
                $fault->resolution_note = 'Ekipman tamir edildi';
                $fault->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Ekipman başarıyla tamir edildi!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete maintenance
     */
    public function completeMaintenance($id)
    {
        try {
            $equipmentStock = EquipmentStock::findOrFail($id);
            
            // Ekipman durumunu aktif yap
            $equipmentStock->status = 'Aktif';
            $equipmentStock->save();
            
            // Aktif bakım kaydını çözüldü olarak işaretle
            $fault = \App\Models\Fault::where('equipment_stock_id', $id)
                ->where('type', 'bakım')
                ->whereIn('status', ['Beklemede', 'İşlemde'])
                ->first();
                
            if ($fault) {
                $fault->status = 'Çözüldü';
                $fault->resolved_date = now()->toDateString();
                $fault->resolved_by = auth()->id();
                $fault->resolution_note = 'Bakım tamamlandı';
                $fault->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Bakım başarıyla tamamlandı!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
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
     * Update a single field of equipment stock (for inline editing)
     */
    public function updateField(Request $request, $id)
    {
        try {
            $equipmentStock = EquipmentStock::with(['equipment.images'])->findOrFail($id);
            
            $field = $request->input('field');
            
            // Resim yükleme işlemi
            if ($field === 'photo') {
                // Debug için log ekle
                \Log::info('Photo upload attempt', [
                    'equipment_stock_id' => $id,
                    'has_file' => $request->hasFile('photo'),
                    'equipment' => $equipmentStock->equipment ? $equipmentStock->equipment->id : 'null'
                ]);
                
                $request->validate([
                    'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
                ]);
                
                // Eski resmi sil
                if ($equipmentStock->equipment && $equipmentStock->equipment->images) {
                    foreach ($equipmentStock->equipment->images as $image) {
                        if (file_exists(public_path('storage/' . $image->path))) {
                            unlink(public_path('storage/' . $image->path));
                        }
                        $image->delete();
                    }
                }
                
                // Yeni resmi yükle
                $photoPath = $request->file('photo')->store('equipment_photos', 'public');
                
                // EquipmentImage tablosuna kaydet
                if ($equipmentStock->equipment) {
                    EquipmentImage::create([
                        'equipment_id' => $equipmentStock->equipment->id,
                        'path' => $photoPath,
                        'is_primary' => true
                    ]);
                }
                
                $imageUrl = asset('storage/' . $photoPath);
                
                \Log::info('Photo upload success', [
                    'photo_path' => $photoPath,
                    'image_url' => $imageUrl
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Resim başarıyla yüklendi',
                    'image_url' => $imageUrl
                ]);
            }
            
            $value = $request->input('value');
            
            // Allowed fields for update
            $allowedFields = ['code', 'brand', 'model', 'size', 'feature', 'quantity', 'status', 'note', 'equipment_name'];
            
            if (!in_array($field, $allowedFields)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Geçersiz alan adı'
                ], 400);
            }
            
            // Individual tracking kontrolü
            if ($field === 'quantity' && $equipmentStock->equipment && $equipmentStock->equipment->individual_tracking) {
                // Ayrı takip özelliği olan ekipmanlarda quantity her zaman 1 olmalı
                $value = 1;
            }
            
            // Validation based on field
            $validationRules = [];
            switch ($field) {
                case 'code':
                    $validationRules['value'] = 'nullable|string|max:255';
                    break;
                case 'brand':
                case 'model':
                case 'size':
                    $validationRules['value'] = 'nullable|string|max:255';
                    break;
                case 'feature':
                    $validationRules['value'] = 'nullable|string|max:1000';
                    break;
                case 'quantity':
                    $validationRules['value'] = 'nullable|integer|min:0';
                    break;
                case 'status':
                    $validationRules['value'] = 'nullable|string|max:255';
                    break;
                case 'note':
                    $validationRules['value'] = 'nullable|string|max:1000';
                    break;
                case 'equipment_name':
                    $validationRules['value'] = 'nullable|string|max:255';
                    // equipment_name güncellenirse, ilgili Equipment tablosunu da güncelle
                    if ($equipmentStock->equipment) {
                        $equipmentStock->equipment->update(['name' => $value]);
                        return response()->json([
                            'success' => true,
                            'message' => 'Ekipman adı başarıyla güncellendi',
                            'data' => [
                                'field' => $field,
                                'value' => $value
                            ]
                        ]);
                    }
                    break;
            }
            
            $request->validate($validationRules);
            
            // Update the field
            $equipmentStock->update([$field => $value]);
            
            return response()->json([
                'success' => true,
                'message' => 'Alan başarıyla güncellendi',
                'data' => [
                    'field' => $field,
                    'value' => $value
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasyon hatası',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Güncelleme sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
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
            
            // Birim türü etiketini ekle
            $equipmentStock->equipment->unit_type_label = $equipmentStock->equipment->unit_type_label;
            
            // Debug: Resim bilgilerini logla
            \Log::info('Equipment images:', [
                'equipment_id' => $equipmentStock->equipment->id,
                'images_count' => $equipmentStock->equipment->images->count(),
                'first_image' => $equipmentStock->equipment->images->first() ? $equipmentStock->equipment->images->first()->path : 'null'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $equipmentStock
        ]);
    }

    /**
     * Get equipment details with stock information
     */
    public function getEquipmentDetails($id)
    {
        try {
            $equipment = Equipment::with(['category', 'images'])->findOrFail($id);
            
            // Get all stock records for this equipment
            $stocks = EquipmentStock::where('equipment_id', $id)->get();
            
            // Calculate total quantity
            $totalQuantity = $stocks->sum('quantity');
            
            // Prepare equipment data with stock info
            $equipmentData = [
                'id' => $equipment->id,
                'name' => $equipment->name,
                'category' => $equipment->category,
                'unit_type' => $equipment->unit_type,
                'unit_type_label' => $equipment->unit_type_label,
                'critical_level' => $equipment->critical_level,
                'individual_tracking' => $equipment->individual_tracking,
                'status' => $equipment->status,
                'note' => $equipment->note,
                'feature' => $equipment->feature,
                'total_quantity' => $totalQuantity,
                'stocks' => $stocks,
                'images' => $equipment->images
            ];
            
            return response()->json([
                'success' => true,
                'data' => $equipmentData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ekipman detayları yüklenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get equipment stocks only
     */
    public function getEquipmentStocks($id)
    {
        try {
            // Get all stock records for this equipment
            $stocks = EquipmentStock::where('equipment_id', $id)->get();
            
            return response()->json([
                'success' => true,
                'stocks' => $stocks
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Stok verileri yüklenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
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
     * Delete equipment and all its stocks
     */
    public function destroyEquipment($id)
    {
        try {
            $equipment = Equipment::findOrFail($id);
            
            // Delete all stock records for this equipment
            EquipmentStock::where('equipment_id', $id)->delete();
            
            // Delete equipment images
            EquipmentImage::where('equipment_id', $id)->delete();
            
            // Delete the equipment
            $equipment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ekipman ve tüm stok kayıtları başarıyla silindi'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ekipman silinirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download QR code
     */
    public function downloadQrCode($id)
    {
        $equipmentStock = EquipmentStock::with('equipment')->findOrFail($id);
        
        if (!$equipmentStock->qr_code) {
            $equipmentStock->generateQrCode();
        }

        $qrCodeData = base64_decode($equipmentStock->qr_code);
        
        $filename = 'qr_code_' . $equipmentStock->code . '.png';
        
        return response($qrCodeData)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get statistics for stock
     */
    public function getStatistics()
    {
        $totalStocks = EquipmentStock::count();
        $activeStocks = EquipmentStock::where('status', 'Aktif')->count();
        $lowStocks = EquipmentStock::count();
        $emptyStocks = EquipmentStock::where('quantity', '<=', 0)->count();

        return response()->json([
            'total' => $totalStocks,
            'active' => $activeStocks,
            'low' => $lowStocks,
            'empty' => $emptyStocks
        ]);
    }

    /**
     * Validate stock code
     */
    public function validateCode(Request $request)
    {
        $code = $request->get('code');
        $equipmentId = $request->get('equipment_id');
        $operationType = $request->get('operation_type', 'in'); // Stok girişi/çıkışı
        
        if (!$code || trim($code) === '') {
            return response()->json([
                'valid' => true,
                'message' => 'Kod boş bırakılabilir'
            ]);
        }
        
        // Stok çıkışında: Kodun mevcut olup olmadığını kontrol et
        if ($operationType === 'out') {
            if ($equipmentId) {
                $equipment = Equipment::find($equipmentId);
                if ($equipment && $equipment->individual_tracking) {
                    // Ayrı takip: Bu ekipman için bu kod var mı?
                    $exists = EquipmentStock::where('equipment_id', $equipmentId)
                        ->where('code', $code)
                        ->exists();
                    
                    return response()->json([
                        'valid' => $exists,
                        'message' => $exists ? 'Kod bulundu' : 'Bu kod bu ekipman için bulunamadı'
                    ]);
                } else {
                    // Toplu takip: Bu kod var mı?
                    $exists = EquipmentStock::where('code', $code)->exists();
                    
                    return response()->json([
                        'valid' => $exists,
                        'message' => $exists ? 'Kod bulundu' : 'Bu kod bulunamadı'
                    ]);
                }
            }
        }
        
        // Stok girişinde: Kod format ve unique kontrolü
        // Kod format kontrolü (en az 3 karakter, alfanumerik)
        if (strlen($code) < 3 || !preg_match('/^[A-Za-z0-9\-_]+$/', $code)) {
            return response()->json([
                'valid' => false,
                'message' => 'Kod en az 3 karakter olmalı ve sadece harf, rakam, tire, alt çizgi içermeli'
            ]);
        }
        
        // Ayrı takip ekipmanları için aynı kod kullanılabilir (sadece aynı ekipman için)
        if ($equipmentId) {
            $equipment = Equipment::find($equipmentId);
            if ($equipment && $equipment->individual_tracking) {
                // Aynı ekipman için aynı kod kontrolü
                $exists = EquipmentStock::where('equipment_id', $equipmentId)
                    ->where('code', $code)
                    ->exists();
                
                return response()->json([
                    'valid' => !$exists,
                    'message' => $exists ? 'Bu kod bu ekipman için zaten kullanılıyor' : 'Kod kullanılabilir'
                ]);
            }
        }
        
        // Toplu takip ekipmanları için global unique kontrol
        $exists = EquipmentStock::where('code', $code)->exists();
        
        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'Bu kod zaten kullanılıyor' : 'Kod kullanılabilir'
        ]);
    }

    /**
     * Validate reference code
     */
    public function validateReferenceCode(Request $request)
    {
        $code = $request->get('code');
        $stock = EquipmentStock::where('code', $code)->first();
        
        if (!$stock) {
            return response()->json([
                'valid' => false,
                'message' => 'Bu stok kodu bulunamadı'
            ]);
        }

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

    /**
     * Get equipment info
     */
    public function getEquipmentInfo($id)
    {
        $equipment = Equipment::with(['category'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $equipment
        ]);
    }

    /**
     * Get existing codes for an equipment (individual tracking)
     */
    public function getEquipmentCodes($id)
    {
        $equipment = Equipment::findOrFail($id);
        if (!$equipment->individual_tracking) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $codes = EquipmentStock::where('equipment_id', $equipment->id)
            ->whereNotNull('code')
            ->pluck('code')
            ->filter() // remove null/empty
            ->values();

        return response()->json([
            'success' => true,
            'data' => $codes
        ]);
    }

    /**
     * Get detailed stock codes for equipment
     */
    public function getDetailedStockCodes($id)
    {
        $equipment = Equipment::findOrFail($id);
        
        $stocks = EquipmentStock::where('equipment_id', $equipment->id)
            ->select('id', 'code', 'brand', 'model', 'size', 'feature', 'quantity', 'note', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'codes' => $stocks
        ]);
    }

    /**
     * Bulk delete stocks
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->get('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Hiç ekipman seçilmedi']);
        }

        // Equipment ID'leri ile Equipment ve tüm EquipmentStock'larını sil
        foreach ($ids as $equipmentId) {
            $equipment = Equipment::find($equipmentId);
            if ($equipment) {
                // EquipmentStock'ları sil
                EquipmentStock::where('equipment_id', $equipmentId)->delete();
                // EquipmentImage'ları sil
                EquipmentImage::where('equipment_id', $equipmentId)->delete();
                // Equipment'i sil
                $equipment->delete();
            }
        }
        
        return response()->json(['success' => true, 'message' => 'Seçili ekipmanlar başarıyla silindi']);
    }

    /**
     * Stock operations (in/out)
     */
    public function stockOperation(Request $request, $id)
    {
        try {
            \Log::info('Stock operation request:', [
                'id' => $id,
                'operation_type' => $request->operation_type,
                'amount' => $request->amount,
                'all_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);
        
        // Validation öncesi kontrol
        if (!$request->operation_type) {
            \Log::error('operation_type is null or empty');
            return response()->json([
                'success' => false,
                'message' => 'İşlem türü belirtilmedi'
            ], 400);
        }
        
        $request->validate([
            'operation_type' => 'required|in:in,out',
            'amount' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'code' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'feature' => 'nullable|string',
            'use_same_properties' => 'nullable|boolean',
            'use_single_image' => 'nullable|boolean',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $equipment = Equipment::findOrFail($id);
        $operationType = $request->operation_type;
        $amount = $request->amount;

        if ($operationType === 'in') {
            if ($equipment->individual_tracking) {
                // Ayrı takip: Her ekipman için ayrı kayıt oluştur
                // Önce mevcut stok kaydından özellikleri kopyala
                $existingStock = EquipmentStock::where('equipment_id', $equipment->id)->first();
                
                $brand = $request->brand ?: ($existingStock ? $existingStock->brand : null);
                $model = $request->model ?: ($existingStock ? $existingStock->model : null);
                $size = $request->size ?: ($existingStock ? $existingStock->size : null);
                $feature = $request->feature ?: ($existingStock ? $existingStock->feature : null);
                
                for ($i = 0; $i < $amount; $i++) {
                    $code = $request->code ?: $this->generateRandomCode();
                    
                    $equipmentStock = EquipmentStock::create([
                        'equipment_id' => $equipment->id,
                        'code' => $code,
                        'brand' => $brand,
                        'model' => $model,
                        'size' => $size,
                        'feature' => $feature,
                        'quantity' => 1, // Ayrı takipte her zaman 1
                        'status' => 'Aktif',
                        'note' => $request->note
                    ]);
                    
                    // QR kod oluştur
                    $qrCode = $this->generateQrCode($equipmentStock);
                    $equipmentStock->update(['qr_code' => $qrCode]);
                }
            } else {
                // Toplu takip: Sadece equipment_id'ye göre mevcut stok kaydını bul
                $existingStock = EquipmentStock::where('equipment_id', $equipment->id)->first();
                
                if ($existingStock) {
                    // Mevcut stok varsa miktarı artır
                    $existingStock->quantity += $amount;
                    $existingStock->save();
                } else {
                    // Yeni stok kaydı oluştur
                    $code = $request->code ?: $this->generateRandomCode();
                    
                    $equipmentStock = EquipmentStock::create([
                        'equipment_id' => $equipment->id,
                        'code' => $code,
                        'brand' => $request->brand ?: null,
                        'model' => $request->model ?: null,
                        'size' => $request->size ?: null,
                        'feature' => $request->feature ?: null,
                        'quantity' => $amount,
                        'status' => 'Aktif',
                        'note' => $request->note
                    ]);
                    
                    // QR kod oluştur
                    $qrCode = $this->generateQrCode($equipmentStock);
                    $equipmentStock->update(['qr_code' => $qrCode]);
                }
            }
        } else {
            // Stok çıkışı
            if ($equipment->individual_tracking) {
                // Ayrı takip: Belirtilen miktar kadar kayıt sil
                $stocksToDelete = EquipmentStock::where('equipment_id', $equipment->id)
                    ->limit($amount)
                    ->get();
                
                if ($stocksToDelete->count() < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Yetersiz stok miktarı'
            ]);
        }

                foreach ($stocksToDelete as $stock) {
                    $stock->delete();
                }
        } else {
                // Toplu takip: Sadece equipment_id'ye göre stok kaydını bul
                $stock = EquipmentStock::where('equipment_id', $equipment->id)->first();
                
                if (!$stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok bulunamadı'
                    ]);
                }
                
                if ($stock->quantity < $amount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Yetersiz stok miktarı'
                    ]);
                }
                
                $stock->quantity -= $amount;
        $stock->save();

                // Miktar 0 olursa stok kaydını sil
                if ($stock->quantity <= 0) {
                    $stock->delete();
                }
            }
        }

        $response = [
            'success' => true,
            'message' => 'Stok işlemi başarıyla gerçekleştirildi'
        ];
        
        \Log::info('Stock operation response:', $response);
        
        return response()->json($response);
        
        } catch (\Exception $e) {
            \Log::error('Stock operation error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Stok işlemi sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Excel şablonu indir
     */
    public function downloadExcelTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Başlık satırı
        $headers = [
            'A1' => 'Kategori Adı',
            'B1' => 'Ekipman Adı',
            'C1' => 'Kod',
            'D1' => 'Marka',
            'E1' => 'Model',
            'F1' => 'Beden',
            'G1' => 'Özellik',
            'H1' => 'Birim Türü',
            'I1' => 'Miktar',
            'J1' => 'Takip Türü',
            'K1' => 'Durum',
            'L1' => 'Not'
        ];

        // Başlıkları yaz
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Örnek veriler
        $sampleData = [
            ['Elektronik', 'Laptop', 'LAP-001', 'Dell', 'Inspiron 15', '15.6"', '8GB RAM, 256GB SSD', 'adet', '1', 'Ayrı Takip', 'Aktif', 'Ofis kullanımı için'],
            ['Elektronik', 'Mouse', 'MOU-001', 'Logitech', 'M100', 'Standart', 'USB kablolu', 'adet', '5', 'Toplu Takip', 'Aktif', 'Genel kullanım'],
            ['İnşaat', 'Çekiç', 'CEK-001', 'Bosch', 'GPH 12-30', '500g', 'Profesyonel', 'adet', '3', 'Toplu Takip', 'Aktif', 'İnşaat işleri'],
            ['Ofis', 'Kalem', 'KAL-001', 'Pilot', 'G2', '0.7mm', 'Mavi mürekkep', 'adet', '50', 'Toplu Takip', 'Aktif', 'Ofis malzemesi']
        ];

        // Örnek verileri yaz
        $row = 2;
        foreach ($sampleData as $data) {
            $col = 'A';
            foreach ($data as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }

        // Stil ayarları
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        // Başlık satırını stil
        $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);

        // Sütun genişlikleri
        $columnWidths = [
            'A' => 15, 'B' => 20, 'C' => 12, 'D' => 15, 'E' => 15, 'F' => 10,
            'G' => 25, 'H' => 12, 'I' => 8, 'J' => 12, 'K' => 10, 'L' => 20
        ];

        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Açıklama satırları
        $sheet->setCellValue('A' . ($row + 2), 'AÇIKLAMALAR:');
        $sheet->setCellValue('A' . ($row + 3), '• Kategori Adı: Ekipmanın ait olduğu kategori (yoksa otomatik oluşturulur)');
        $sheet->setCellValue('A' . ($row + 4), '• Takip Türü: "Ayrı Takip" veya "Toplu Takip"');
        $sheet->setCellValue('A' . ($row + 5), '• Birim Türü: adet, metre, kilogram, litre, paket, kutu, çift, takım');
        $sheet->setCellValue('A' . ($row + 6), '• Durum: Aktif, Pasif, Arızalı, Bakımda');
        $sheet->setCellValue('A' . ($row + 7), '• Kod: Boş bırakılırsa otomatik oluşturulur');

        // Açıklama stil
        $sheet->getStyle('A' . ($row + 2) . ':L' . ($row + 7))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($row + 2) . ':L' . ($row + 7))->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

        $writer = new Xlsx($spreadsheet);
        
        $filename = 'ekipman_import_sablonu_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Excel dosyasını önizle
     */
    public function previewExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240'
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            
            $previewData = [];
            $errors = [];
            $criticalErrors = [];
            
            // Başlık satırı kontrolü
            $headerRow = 1;
            $expectedHeaders = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
            foreach ($expectedHeaders as $col) {
                $cellValue = trim($worksheet->getCell($col . $headerRow)->getValue() ?? '');
                if (empty($cellValue)) {
                    $criticalErrors[] = "Sütun {$col} başlığı eksik veya boş";
                }
            }
            
            // İlk satır başlık, 2. satırdan başla
            for ($row = 2; $row <= min($highestRow, 21); $row++) { // İlk 20 satırı önizle
                $categoryName = trim($worksheet->getCell('A' . $row)->getValue() ?? '');
                $equipmentName = trim($worksheet->getCell('B' . $row)->getValue() ?? '');
                $code = trim($worksheet->getCell('C' . $row)->getValue() ?? '');
                $brand = trim($worksheet->getCell('D' . $row)->getValue() ?? '');
                $model = trim($worksheet->getCell('E' . $row)->getValue() ?? '');
                $size = trim($worksheet->getCell('F' . $row)->getValue() ?? '');
                $feature = trim($worksheet->getCell('G' . $row)->getValue() ?? '');
                $unitType = trim($worksheet->getCell('H' . $row)->getValue() ?? '');
                $quantity = trim($worksheet->getCell('I' . $row)->getValue() ?? '');
                $trackingType = trim($worksheet->getCell('J' . $row)->getValue() ?? '');
                $status = trim($worksheet->getCell('K' . $row)->getValue() ?? '');
                $note = trim($worksheet->getCell('L' . $row)->getValue() ?? '');
                
                // Boş satırları atla
                if (empty($categoryName) && empty($equipmentName)) {
                    continue;
                }
                
                // Açıklama satırlarını atla
                if (strpos($categoryName, 'AÇIKLAMALAR') === 0 || 
                    strpos($categoryName, '•') === 0 ||
                    strpos($equipmentName, 'AÇIKLAMALAR') === 0 ||
                    strpos($equipmentName, '•') === 0) {
                    continue;
                }
                
                // Kapsamlı validasyon
                $rowErrors = [];
                $rowWarnings = [];
                
                // Zorunlu alanlar
                if (empty($categoryName)) {
                    $rowErrors[] = 'Kategori adı gerekli';
                } else {
                    // Kategori adı validasyonu
                    if (strlen($categoryName) > 100) {
                        $rowErrors[] = 'Kategori adı 100 karakterden uzun olamaz';
                    }
                    if (preg_match('/[<>"\'&]/', $categoryName)) {
                        $rowErrors[] = 'Kategori adında özel karakterler (<>"\'&) bulunamaz';
                    }
                    // Sadece boşluk kontrolü
                    if (trim($categoryName) === '') {
                        $rowErrors[] = 'Kategori adı sadece boşluk olamaz';
                    }
                    // Sayısal kontrol
                    if (is_numeric($categoryName)) {
                        $rowErrors[] = 'Kategori adı sadece sayı olamaz';
                    }
                }
                
                if (empty($equipmentName)) {
                    $rowErrors[] = 'Ekipman adı gerekli';
                } else {
                    // Ekipman adı validasyonu
                    if (strlen($equipmentName) > 100) {
                        $rowErrors[] = 'Ekipman adı 100 karakterden uzun olamaz';
                    }
                    if (preg_match('/[<>"\'&]/', $equipmentName)) {
                        $rowErrors[] = 'Ekipman adında özel karakterler (<>"\'&) bulunamaz';
                    }
                    // Sadece boşluk kontrolü
                    if (trim($equipmentName) === '') {
                        $rowErrors[] = 'Ekipman adı sadece boşluk olamaz';
                    }
                    // Sayısal kontrol
                    if (is_numeric($equipmentName)) {
                        $rowErrors[] = 'Ekipman adı sadece sayı olamaz';
                    }
                }
                
                // Kod validasyonu
                if (!empty($code)) {
                    if (strlen($code) > 50) {
                        $rowErrors[] = 'Kod 50 karakterden uzun olamaz';
                    }
                    if (preg_match('/[<>"\'&]/', $code)) {
                        $rowErrors[] = 'Kodda özel karakterler (<>"\'&) bulunamaz';
                    }
                    // Sadece boşluk kontrolü
                    if (trim($code) === '') {
                        $rowErrors[] = 'Kod sadece boşluk olamaz';
                    }
                    // Özel karakterler (sadece harf, rakam, tire, alt çizgi)
                    if (!preg_match('/^[A-Za-z0-9\-_]+$/', trim($code))) {
                        $rowErrors[] = 'Kod sadece harf, rakam, tire (-) ve alt çizgi (_) içerebilir';
                    }
                    // Mevcut kod kontrolü (sadece ayrı takip ekipmanları için)
                    if (!empty($trackingType) && in_array($trackingType, ['Ayrı Takip', 'ayrı takip', 'AYRI TAKİP', 'Ayrı', 'ayrı', 'AYRI'])) {
                        if (EquipmentStock::where('code', trim($code))->exists()) {
                            $rowWarnings[] = 'Bu kod zaten kullanımda (ayrı takip ekipmanı)';
                        }
                    }
                }
                
                // Marka validasyonu
                if (!empty($brand) && strlen($brand) > 50) {
                    $rowErrors[] = 'Marka 50 karakterden uzun olamaz';
                }
                if (!empty($brand) && preg_match('/[<>"\'&]/', $brand)) {
                    $rowErrors[] = 'Markada özel karakterler (<>"\'&) bulunamaz';
                }
                
                // Model validasyonu
                if (!empty($model) && strlen($model) > 50) {
                    $rowErrors[] = 'Model 50 karakterden uzun olamaz';
                }
                if (!empty($model) && preg_match('/[<>"\'&]/', $model)) {
                    $rowErrors[] = 'Modelde özel karakterler (<>"\'&) bulunamaz';
                }
                
                // Beden validasyonu
                if (!empty($size) && strlen($size) > 50) {
                    $rowErrors[] = 'Beden 50 karakterden uzun olamaz';
                }
                
                // Özellik validasyonu
                if (!empty($feature) && strlen($feature) > 500) {
                    $rowErrors[] = 'Özellik 500 karakterden uzun olamaz';
                }
                
                // Birim türü validasyonu
                if (!empty($unitType)) {
                    $validUnitTypes = ['adet', 'metre', 'kilogram', 'litre', 'paket', 'kutu', 'çift', 'takım', 'Adet', 'Metre', 'Kilogram', 'Litre', 'Paket', 'Kutu', 'Çift', 'Takım'];
                    if (!in_array($unitType, $validUnitTypes)) {
                        $rowErrors[] = 'Geçersiz birim türü. Geçerli değerler: adet, metre, kilogram, litre, paket, kutu, çift, takım';
                    }
                }
                
                // Miktar validasyonu
                if (!empty($quantity)) {
                    if (!is_numeric($quantity)) {
                        $rowErrors[] = 'Miktar sayısal değer olmalıdır';
                    } elseif ((int)$quantity < 1) {
                        $rowErrors[] = 'Miktar 1\'den küçük olamaz';
                    } elseif ((int)$quantity > 10000) {
                        $rowErrors[] = 'Miktar 10,000\'dan büyük olamaz';
                    }
                }
                
                // Takip türü validasyonu
                if (!empty($trackingType)) {
                    $validTrackingTypes = ['Toplu Takip', 'Ayrı Takip', 'toplu takip', 'ayrı takip', 'TOPLU TAKİP', 'AYRI TAKİP', 'Toplu', 'Ayrı', 'toplu', 'ayrı', 'TOPLU', 'AYRI'];
                    if (!in_array($trackingType, $validTrackingTypes)) {
                        $rowErrors[] = 'Geçersiz takip türü. Geçerli değerler: Toplu Takip, Ayrı Takip';
                    }
                }
                
                // Durum validasyonu
                if (!empty($status)) {
                    $validStatuses = [
                        'Aktif', 'Pasif', 'Arızalı', 'Bakımda', 'Yok',
                        'aktif', 'pasif', 'arızalı', 'bakımda', 'yok',
                        'AKTİF', 'PASİF', 'ARIYALI', 'BAKIMDA', 'YOK',
                        'Active', 'Passive', 'Faulty', 'Maintenance', 'None',
                        'active', 'passive', 'faulty', 'maintenance', 'none'
                    ];
                    if (!in_array($status, $validStatuses)) {
                        $rowErrors[] = 'Geçersiz durum. Geçerli değerler: Aktif, Pasif, Arızalı, Bakımda, Yok';
                    }
                }
                
                // Not validasyonu
                if (!empty($note) && strlen($note) > 1000) {
                    $rowErrors[] = 'Not 1000 karakterden uzun olamaz';
                }
                if (!empty($note) && preg_match('/[<>"\'&]/', $note)) {
                    $rowErrors[] = 'Notta özel karakterler (<>"\'&) bulunamaz';
                }
                
                $previewData[] = [
                    'row' => $row,
                    'category_name' => $categoryName,
                    'equipment_name' => $equipmentName,
                    'code' => $code ?: 'Otomatik oluşturulacak',
                    'brand' => $brand,
                    'model' => $model,
                    'size' => $size,
                    'feature' => $feature,
                    'unit_type' => $unitType ?: 'adet',
                    'quantity' => $quantity ?: '1',
                    'tracking_type' => $trackingType ?: 'Toplu Takip',
                    'status' => $status ?: 'Aktif',
                    'note' => $note,
                    'errors' => $rowErrors,
                    'warnings' => $rowWarnings
                ];
                
                if (!empty($rowErrors)) {
                    $errors[] = "Satır {$row}: " . implode(', ', $rowErrors);
                }
            }
            
            // Kritik hatalar varsa işlemi durdur
            if (!empty($criticalErrors)) {
                return response()->json([
                    'success' => false,
                    'critical_errors' => $criticalErrors,
                    'message' => 'Excel dosyası formatı hatalı. Lütfen şablon dosyasını kullanın.'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'preview' => $previewData,
                'total_rows' => $highestRow - 1, // Başlık satırını çıkar
                'errors' => $errors,
                'has_errors' => !empty($errors)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Excel dosyası okunamadı: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Excel dosyasını içe aktar
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240'
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            
            $summary = [
                'success' => 0,
                'skipped' => 0,
                'errors' => 0,
                'categories_created' => 0,
                'equipments_created' => 0,
                'stocks_created' => 0
            ];
            $errors = [];
            $skippedRows = [];
            $autoAssign = $request->boolean('auto_assign_codes', false);
            $skipDuplicates = $request->boolean('skip_duplicates', true);
            $createCategories = $request->boolean('create_categories', true);
            
            // Önce tüm verileri validate et
            $validationErrors = [];
            $validRows = [];
            
            for ($row = 2; $row <= $highestRow; $row++) {
                $categoryName = trim($worksheet->getCell('A' . $row)->getValue() ?? '');
                $equipmentName = trim($worksheet->getCell('B' . $row)->getValue() ?? '');
                $code = trim($worksheet->getCell('C' . $row)->getValue() ?? '');
                $brand = trim($worksheet->getCell('D' . $row)->getValue() ?? '');
                $model = trim($worksheet->getCell('E' . $row)->getValue() ?? '');
                $size = trim($worksheet->getCell('F' . $row)->getValue() ?? '');
                $feature = trim($worksheet->getCell('G' . $row)->getValue() ?? '');
                $unitType = trim($worksheet->getCell('H' . $row)->getValue() ?? '');
                $quantity = trim($worksheet->getCell('I' . $row)->getValue() ?? '');
                $trackingType = trim($worksheet->getCell('J' . $row)->getValue() ?? '');
                $status = trim($worksheet->getCell('K' . $row)->getValue() ?? '');
                $note = trim($worksheet->getCell('L' . $row)->getValue() ?? '');
                
                // Boş satırları atla
                if (empty($categoryName) && empty($equipmentName)) {
                    continue;
                }
                
                // Açıklama satırlarını atla
                if (strpos($categoryName, 'AÇIKLAMALAR') === 0 || 
                    strpos($categoryName, '•') === 0 ||
                    strpos($equipmentName, 'AÇIKLAMALAR') === 0 ||
                    strpos($equipmentName, '•') === 0) {
                    continue;
                }
                
                $rowErrors = [];
                
                // Kritik validasyonlar
                if (empty($categoryName)) {
                    $rowErrors[] = 'Kategori adı gerekli';
                } elseif (strlen($categoryName) > 100) {
                    $rowErrors[] = 'Kategori adı 100 karakterden uzun olamaz';
                } elseif (preg_match('/[<>"\'&]/', $categoryName)) {
                    $rowErrors[] = 'Kategori adında özel karakterler (<>"\'&) bulunamaz';
                }
                
                if (empty($equipmentName)) {
                    $rowErrors[] = 'Ekipman adı gerekli';
                } elseif (strlen($equipmentName) > 100) {
                    $rowErrors[] = 'Ekipman adı 100 karakterden uzun olamaz';
                } elseif (preg_match('/[<>"\'&]/', $equipmentName)) {
                    $rowErrors[] = 'Ekipman adında özel karakterler (<>"\'&) bulunamaz';
                }
                
                // Kod validasyonu
                if (!empty($code)) {
                    if (strlen($code) > 50) {
                        $rowErrors[] = 'Kod 50 karakterden uzun olamaz';
                    } elseif (preg_match('/[<>"\'&]/', $code)) {
                        $rowErrors[] = 'Kodda özel karakterler (<>"\'&) bulunamaz';
                    } elseif (!empty($trackingType) && in_array($trackingType, ['Ayrı Takip', 'ayrı takip', 'AYRI TAKİP', 'Ayrı', 'ayrı', 'AYRI']) && EquipmentStock::where('code', $code)->exists()) {
                        if (!$skipDuplicates && !$autoAssign) {
                            $rowErrors[] = 'Bu kod zaten kullanımda (ayrı takip ekipmanı)';
                        }
                    }
                }
                
                // Diğer alan validasyonları
                if (!empty($brand) && strlen($brand) > 50) {
                    $rowErrors[] = 'Marka 50 karakterden uzun olamaz';
                }
                if (!empty($model) && strlen($model) > 50) {
                    $rowErrors[] = 'Model 50 karakterden uzun olamaz';
                }
                if (!empty($size) && strlen($size) > 50) {
                    $rowErrors[] = 'Beden 50 karakterden uzun olamaz';
                }
                if (!empty($feature) && strlen($feature) > 500) {
                    $rowErrors[] = 'Özellik 500 karakterden uzun olamaz';
                }
                if (!empty($note) && strlen($note) > 1000) {
                    $rowErrors[] = 'Not 1000 karakterden uzun olamaz';
                }
                
                // Miktar validasyonu
                if (!empty($quantity) && (!is_numeric($quantity) || (int)$quantity < 1 || (int)$quantity > 10000)) {
                    $rowErrors[] = 'Miktar geçersiz (1-10,000 arası sayı olmalı)';
                }
                
                // Birim türü validasyonu - normalizasyon ile kontrol
                if (!empty($unitType)) {
                    $normalizedUnitType = $this->normalizeUnitType($unitType);
                    $validUnitTypes = ['adet', 'metre', 'kilogram', 'litre', 'paket', 'kutu', 'çift', 'takım'];
                    if (!in_array($normalizedUnitType, $validUnitTypes)) {
                        $rowErrors[] = 'Geçersiz birim türü. Geçerli değerler: adet, metre, kilogram, litre, paket, kutu, çift, takım';
                    }
                }
                
                // Takip türü validasyonu - normalizasyon ile kontrol (daha esnek)
                if (!empty($trackingType)) {
                    // NormalizeTrackingType fonksiyonu boolean döndürür, bu yüzden sadece kontrol ediyoruz
                    $normalizedTrackingType = $this->normalizeTrackingType($trackingType);
                    // Burada hata vermiyoruz çünkü fonksiyon zaten esnek
                }
                
                // Durum validasyonu
                if (!empty($status)) {
                    $validStatuses = [
                        'Aktif', 'Pasif', 'Arızalı', 'Bakımda', 'Yok',
                        'aktif', 'pasif', 'arızalı', 'bakımda', 'yok',
                        'AKTİF', 'PASİF', 'ARIYALI', 'BAKIMDA', 'YOK',
                        'Active', 'Passive', 'Faulty', 'Maintenance', 'None',
                        'active', 'passive', 'faulty', 'maintenance', 'none'
                    ];
                    if (!in_array($status, $validStatuses)) {
                        $rowErrors[] = 'Geçersiz durum. Geçerli değerler: Aktif, Pasif, Arızalı, Bakımda, Yok';
                    }
                }
                
                if (!empty($rowErrors)) {
                    $validationErrors[] = "Satır {$row}: " . implode(', ', $rowErrors);
                } else {
                    // Normalize values before storing
                    $normalizedUnitType = $this->normalizeUnitType($unitType);
                    $normalizedTrackingType = $this->normalizeTrackingType($trackingType);
                    
                    $validRows[] = [
                        'row' => $row,
                        'category_name' => $categoryName,
                        'equipment_name' => $equipmentName,
                        'code' => $code,
                        'brand' => $brand,
                        'model' => $model,
                        'size' => $size,
                        'feature' => $feature,
                        'unit_type' => $normalizedUnitType,
                        'quantity' => $quantity,
                        'tracking_type' => $normalizedTrackingType,
                        'status' => $status,
                        'note' => $note
                    ];
                }
            }
            
            // Hataları kaydet ama işlemi durdurma
            $summary['errors'] = count($validationErrors);
            
            // Validasyon başarılı, import işlemini başlat
            foreach ($validRows as $rowData) {
                try {
                    $categoryName = $rowData['category_name'];
                    $equipmentName = $rowData['equipment_name'];
                    $code = $rowData['code'];
                    $brand = $rowData['brand'];
                    $model = $rowData['model'];
                    $size = $rowData['size'];
                    $feature = $rowData['feature'];
                    $unitType = $rowData['unit_type'];
                    $quantity = $rowData['quantity'];
                    $trackingType = $rowData['tracking_type'];
                    $status = $rowData['status'];
                    $note = $rowData['note'];
                    
                    // Kategori kontrolü ve oluşturma
                    $category = EquipmentCategory::where('name', $categoryName)->first();
                    if (!$category && $createCategories) {
                        $category = EquipmentCategory::create([
                            'name' => $categoryName,
                            'description' => 'Excel import ile oluşturuldu'
                        ]);
                        $summary['categories_created']++;
                    } elseif (!$category) {
                        $errors[] = "Satır {$rowData['row']}: Kategori '{$categoryName}' bulunamadı";
                        $summary['errors']++;
                        continue;
                    }
                    
                    // Ekipman kontrolü ve oluşturma
                    $equipment = Equipment::where('name', $equipmentName)
                        ->where('category_id', $category->id)
                        ->first();
                    
                    $isNewEquipment = false;
                    if (!$equipment) {
                        $equipment = Equipment::create([
                            'name' => $equipmentName,
                            'category_id' => $category->id,
                            'unit_type' => $unitType, // Zaten normalize edilmiş
                            'individual_tracking' => $trackingType // Zaten normalize edilmiş (boolean)
                        ]);
                        $summary['equipments_created']++;
                        $isNewEquipment = true;
                    }
                    
                    // Takip türüne göre işlem
                    if ($trackingType) { // Zaten normalize edilmiş boolean değer
                        // Ayrı takip: Her ekipman için ayrı stok kaydı
                        if (empty($code)) {
                            $code = $this->generateRandomCode();
                        }
                        
                        // Mükerrer kod kontrolü
                        $existingStock = EquipmentStock::where('code', $code)->first();
                        if ($existingStock) {
                            if ($autoAssign) {
                                $code = $this->generateRandomCode();
                            } else if ($skipDuplicates) {
                                $summary['skipped']++;
                                $skippedRows[] = "Satır {$rowData['row']}: Kod '{$code}' zaten mevcut (atlandı).";
                                continue;
                            } else {
                                $errors[] = "Satır {$rowData['row']}: Kod '{$code}' zaten mevcut";
                                $summary['errors']++;
                                continue;
                            }
                        }
                        
                        // Yeni stok kaydı oluştur
                        EquipmentStock::create([
                            'equipment_id' => $equipment->id,
                            'code' => $code,
                            'brand' => $brand,
                            'model' => $model,
                            'size' => $size,
                            'feature' => $feature,
                            'quantity' => 1, // Ayrı takip için her zaman 1
                            'status' => $status ?: 'Aktif',
                            'note' => $note
                        ]);
                        $summary['stocks_created']++;
                        
                    } else {
                        // Toplu takip: Aynı ekipman için miktarı artır
                        $existingStock = EquipmentStock::where('equipment_id', $equipment->id)
                            ->where('brand', $brand)
                            ->where('model', $model)
                            ->where('size', $size)
                            ->where('feature', $feature)
                            ->first();
                        
                        if ($existingStock) {
                            // Mevcut stok varsa miktarı artır
                            $existingStock->quantity += is_numeric($quantity) ? (int)$quantity : 1;
                            $existingStock->save();
                        } else {
                            // Yeni stok kaydı oluştur
                            $code = $code ?: $this->generateRandomCode();
                            
                            EquipmentStock::create([
                                'equipment_id' => $equipment->id,
                                'code' => $code,
                                'brand' => $brand,
                                'model' => $model,
                                'size' => $size,
                                'feature' => $feature,
                                'quantity' => is_numeric($quantity) ? (int)$quantity : 1,
                                'status' => $status ?: 'Aktif',
                                'note' => $note
                            ]);
                            $summary['stocks_created']++;
                        }
                    }
                    
                    $summary['success']++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Satır {$rowData['row']}: " . $e->getMessage();
                    $summary['errors']++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Import işlemi tamamlandı',
                'summary' => $summary,
                'errors' => $errors,
                'skipped_rows' => $skippedRows,
                'validation_errors' => $validationErrors ?? []
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import işlemi başarısız: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get equipment info for search functionality
     */
    public function getEquipmentInfoForSearch($id)
    {
        try {
            $equipment = Equipment::find($id);
            
            if (!$equipment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ekipman bulunamadı'
                ]);
            }

            return response()->json([
                'success' => true,
                'equipment' => [
                    'id' => $equipment->id,
                    'name' => $equipment->name,
                    'code' => $equipment->code,
                    'category' => $equipment->category ? $equipment->category->name : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ekipman bilgisi alınırken hata oluştu: ' . $e->getMessage()
            ]);
        }
    }
}
