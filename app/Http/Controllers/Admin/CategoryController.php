<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Kategoriler';
        
        // Kategorileri sayfalayarak çekiyoruz
        $categories = EquipmentCategory::withCount('equipments')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('admin.category.index', compact('categories', 'pageTitle'));
    }

    /**
     * Get categories data for AJAX requests
     */
    public function getCategoryData(Request $request)
    {
        $query = EquipmentCategory::withCount('equipments');

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Sort filter
        if ($request->has('sort') && !empty($request->sort)) {
            switch ($request->sort) {
                case 'most':
                    $query->orderBy('equipments_count', 'desc');
                    break;
                case 'least':
                    $query->orderBy('equipments_count', 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $categories = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $categories->items(),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:equipment_categories,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:equipment_categories,id'
        ]);

        $category = EquipmentCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori başarıyla oluşturuldu',
            'data' => $category
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = EquipmentCategory::with(['equipments', 'parent', 'children'])
            ->withCount('equipments')
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = EquipmentCategory::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:equipment_categories,name,' . $id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:equipment_categories,id'
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori başarıyla güncellendi',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = EquipmentCategory::findOrFail($id);
        
        // Alt kategorileri kontrol et
        if ($category->children()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Bu kategorinin alt kategorileri var. Önce onları silmelisiniz.'
            ], 400);
        }

        // Ekipmanları kontrol et
        if ($category->equipments()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Bu kategoride ekipmanlar var. Önce onları başka kategorilere taşıyın.'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori başarıyla silindi'
        ]);
    }

    /**
     * Bulk delete categories
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Silinecek kategori seçilmedi'
            ], 400);
        }

        $categories = EquipmentCategory::whereIn('id', $ids)->get();
        $deletedCount = 0;
        $errors = [];

        foreach ($categories as $category) {
            try {
                // Alt kategorileri kontrol et
                if ($category->children()->count() > 0) {
                    $errors[] = "Kategori '{$category->name}' alt kategorilere sahip";
                    continue;
                }

                // Ekipmanları kontrol et
                if ($category->equipments()->count() > 0) {
                    $errors[] = "Kategori '{$category->name}' ekipmanlara sahip";
                    continue;
                }

                $category->delete();
                $deletedCount++;
            } catch (\Exception $e) {
                $errors[] = "Kategori '{$category->name}' silinirken hata oluştu";
            }
        }

        $message = "{$deletedCount} kategori başarıyla silindi";
        if (!empty($errors)) {
            $message .= ". Hatalar: " . implode(', ', $errors);
        }

        return response()->json([
            'success' => $deletedCount > 0,
            'message' => $message,
            'deleted_count' => $deletedCount,
            'errors' => $errors
        ]);
    }

    /**
     * Export categories to CSV
     */
    public function exportCsv()
    {
        $categories = EquipmentCategory::withCount('equipments')->get();
        
        $filename = 'categories_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Sıra', 'Kategori Adı', 'Açıklama', 'Ürün Sayısı', 
                'Üst Kategori', 'Renk', 'İkon', 'Eklenme Tarihi'
            ]);

            foreach ($categories as $index => $category) {
                fputcsv($file, [
                    $index + 1,
                    $category->name ?? '-',
                    $category->description ?? '-',
                    $category->equipments_count ?? 0,
                    $category->parent->name ?? '-',
                    $category->color ?? '-',
                    $category->icon ?? '-',
                    $category->created_at ? $category->created_at->format('d.m.Y') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 