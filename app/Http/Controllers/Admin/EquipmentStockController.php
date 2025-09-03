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
        // QR kod içeriği: ekipman bilgileri
        $qrContent = json_encode([
            'id' => $equipmentStock->id,
            'equipment_name' => $equipmentStock->equipment->name ?? 'Bilinmeyen',
            'code' => $equipmentStock->code,
            'brand' => $equipmentStock->brand,
            'model' => $equipmentStock->model,
            'type' => 'equipment_stock'
        ]);

        // QR kod oluştur ve base64 olarak kaydet
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(10)
            ->generate($qrContent);

        return base64_encode($qrCode);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Stok Yönetimi';
        
        // Ekipman stoklarını, ilgili ekipman bilgisiyle birlikte sayfalayarak çekiyoruz
        // Individual tracking kontrolü: Ayrı takip özelliği olan ekipmanlar için her kayıt ayrı gösterilir
        $stocks = EquipmentStock::with(['equipment.category'])
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

        // Pagination bilgilerini hazırla
        $pagination = [
            'current_page' => $stocks->currentPage(),
            'last_page' => $stocks->lastPage(),
            'per_page' => $stocks->perPage(),
            'total' => $stocks->total()
        ];

        return view('admin.stock.index', compact('stocks', 'pageTitle', 'categories', 'equipmentList', 'pagination'));
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

        $stocks = $query->orderBy('id', 'asc')->paginate(15);

        // Her stok için accessor'ları hesapla
        $stocks->getCollection()->transform(function ($stock) {
            // Ekipman ismi ve kategori bilgilerini ekle
            $stock->name = $stock->equipment->name ?? null;
            $stock->category = $stock->equipment->category ?? null;
            
            // Diğer accessor'ları da hesapla
            $stock->row_class = $stock->getRowClassAttribute();
            $stock->bar_class = $stock->getBarClassAttribute();
            $stock->percentage = $stock->getPercentageAttribute();
            $stock->status_badge = $stock->getStatusBadgeAttribute();
            $stock->total_quantity = $stock->getTotalQuantityAttribute();
            $stock->unit_type_label = $stock->getUnitTypeLabelAttribute();
            $stock->critical_level = $stock->getCriticalLevelAttribute();
            
            return $stock;
        });

        // Debug: İlk stok verisini kontrol et
        if ($stocks->count() > 0) {
            $firstStock = $stocks->first();
            \Log::info('First stock data:', [
                'id' => $firstStock->id,
                'name' => $firstStock->name,
                'category' => $firstStock->category ? $firstStock->category->name : 'null',
                'equipment_name' => $firstStock->equipment ? $firstStock->equipment->name : 'null'
            ]);
        }

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:equipment_categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'feature' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'unit_type' => 'required|string',
            'critical_level' => 'nullable|numeric|min:0',
            'code' => 'nullable|string|max:255|unique:stock_depo,code',
            'qr_code' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'stock_status' => 'nullable|string|max:255',
            'next_maintenance_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'note' => 'nullable|string',
            'individual_tracking' => 'nullable|boolean'
        ]);

        // Ekipman oluştur
        $equipment = Equipment::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'critical_level' => $request->critical_level,
            'unit_type' => $request->unit_type,
            'individual_tracking' => $request->individual_tracking ? true : false,
            'status' => Equipment::STATUS_ACTIVE
        ]);

        // Fotoğraf işleme
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('equipment_photos', 'public');
        }

        // Kod oluştur
        $code = $request->code ?: $this->generateRandomCode();

        // Individual tracking kontrolü
        $quantity = $request->individual_tracking ? 1 : $request->quantity;

        // Ekipman stoku oluştur
        $equipmentStock = EquipmentStock::create([
            'equipment_id' => $equipment->id,
            'brand' => $request->brand,
            'model' => $request->model,
            'size' => $request->size,
            'feature' => $request->feature,
            'status' => $request->stock_status ?: 'Aktif',
            'code' => $code,
            'qr_code' => $request->qr_code,
            'location' => $request->location,
            'quantity' => $quantity,
            'note' => $request->note,
            'next_maintenance_date' => $request->next_maintenance_date,
            'photo_path' => $photoPath
        ]);

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

        return redirect()->route('admin.equipments')->with('success', 'Ekipman başarıyla eklendi!');
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
        $stock = EquipmentStock::with(['equipment.category'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $stock
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

        EquipmentStock::whereIn('id', $ids)->delete();
        
        return response()->json(['success' => true, 'message' => 'Seçili ekipmanlar başarıyla silindi']);
    }

    /**
     * Stock operations (in/out)
     */
    public function stockOperation(Request $request, $id)
    {
        $request->validate([
            'operation_type' => 'required|in:in,out',
            'amount' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        $stock = EquipmentStock::findOrFail($id);
        $operationType = $request->operation_type;
        $amount = $request->amount;

        if ($operationType === 'out' && $stock->quantity < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Yetersiz stok miktarı'
            ]);
        }

        if ($operationType === 'in') {
            $stock->quantity += $amount;
        } else {
            $stock->quantity -= $amount;
        }

        $stock->save();

        return response()->json([
            'success' => true,
            'message' => 'Stok işlemi başarıyla gerçekleştirildi',
            'new_quantity' => $stock->quantity
        ]);
    }
}
