<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentStock;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Illuminate\Http\Request;

class EquipmentStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Stok Yönetimi';
        
        // Equipment tablosundan gruplandırılmış veri çekiyoruz
        $stocks = Equipment::with(['category'])
            ->withCount(['stocks as total_quantity' => function($query) {
                $query->select(\DB::raw('SUM(quantity)'));
            }])
            ->orderBy('name', 'asc')
            ->paginate(15);

        // Kategorileri filtre için çekiyoruz
        $categories = EquipmentCategory::orderBy('name', 'asc')->get();

        return view('admin.stock.index', compact('stocks', 'categories', 'pageTitle'));
    }

    /**
     * Get stock data for AJAX requests
     */
    public function getStockData(Request $request)
    {
        $query = Equipment::with(['category'])
            ->withCount(['stocks as total_quantity' => function($query) {
                $query->select(\DB::raw('SUM(quantity)'));
            }]);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        // Category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            switch ($request->status) {
                case 'sufficient':
                    $query->having('total_quantity', '>', 10);
                    break;
                case 'low':
                    $query->having('total_quantity', '<=', 10)->having('total_quantity', '>', 0);
                    break;
                case 'empty':
                    $query->having('total_quantity', 0);
                    break;
            }
        }

        $stocks = $query->orderBy('name', 'asc')->paginate(15);

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'code' => 'required|string|max:255|unique:equipment_stocks,code',
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
     * Stock in/out operations
     */
    public function stockOperation(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'amount' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        // Mevcut toplam stok miktarını hesapla
        $currentTotal = $equipment->stocks()->sum('quantity');

        if ($validated['type'] === 'out' && $currentTotal < $validated['amount']) {
            return response()->json([
                'success' => false,
                'message' => 'Yetersiz stok! Mevcut stok: ' . $currentTotal
            ], 400);
        }

        // Yeni bir EquipmentStock kaydı oluştur veya mevcut olanı güncelle
        if ($validated['type'] === 'in') {
            // Stok girişi - yeni kayıt oluştur
            $equipment->stocks()->create([
                'quantity' => $validated['amount'],
                'note' => $validated['note'] ?? 'Stok girişi',
                'status' => 'active'
            ]);
        } else {
            // Stok çıkışı - mevcut stoklardan düş
            $remainingAmount = $validated['amount'];
            $stocks = $equipment->stocks()->where('quantity', '>', 0)->orderBy('created_at', 'asc')->get();
            
            foreach ($stocks as $stock) {
                if ($remainingAmount <= 0) break;
                
                $deductAmount = min($stock->quantity, $remainingAmount);
                $stock->quantity -= $deductAmount;
                $stock->save();
                
                $remainingAmount -= $deductAmount;
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
