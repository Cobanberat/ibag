<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentStock;
use App\Models\EquipmentCategory;
use Illuminate\Http\Request;

class EquipmentStatusController extends Controller
{
    public function index()
    {
        $pageTitle = 'Ekipman Durumu';
        
        // Test için tüm ekipmanları çek
        $query = EquipmentStock::with(['equipment.category', 'equipment.images']);

        // Arama filtresi
        if (request('search')) {
            $query->whereHas('equipment', function($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        }

        // Kategori filtresi
        if (request('category')) {
            $query->whereHas('equipment.category', function($q) {
                $q->where('id', request('category'));
            });
        }

        // Durum filtresi
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $equipmentStocks = $query->orderBy('updated_at', 'desc')->paginate(12)->withQueryString();

        // Debug - Veri kontrolü
        \Log::info('EquipmentStatus - Query SQL: ' . $query->toSql());
        \Log::info('EquipmentStatus - Bulunan ekipman sayısı: ' . $equipmentStocks->count());
        \Log::info('EquipmentStatus - Toplam: ' . $equipmentStocks->total());
        
        if($equipmentStocks->count() > 0) {
            \Log::info('EquipmentStatus - İlk ekipman: ', $equipmentStocks->first()->toArray());
        } else {
            \Log::info('EquipmentStatus - Hiç ekipman bulunamadı!');
        }

        // Kategorileri çek
        $categories = EquipmentCategory::orderBy('name')->get();

        // Durum istatistikleri
        $stats = [
            'bakim' => EquipmentStock::where(function($query) {
                $query->where('status', 'like', '%bakım%')
                      ->orWhere('status', 'like', '%Bakım%')
                      ->orWhere('status', '=', 'Bakım Gerekiyor')
                      ->orWhere('status', '=', 'bakım gerekiyor')
                      ->orWhere('status', '=', 'BAKIM');
            })->count(),
            'arizali' => EquipmentStock::where(function($query) {
                $query->where('status', 'like', '%arıza%')
                      ->orWhere('status', 'like', '%Arıza%')
                      ->orWhere('status', 'like', '%arızalı%')
                      ->orWhere('status', 'like', '%Arızalı%')
                      ->orWhere('status', '=', 'ARIZA')
                      ->orWhere('status', '=', 'ARIZALI');
            })->count(),
            'toplam' => EquipmentStock::where(function($query) {
                $query->where('status', 'like', '%bakım%')
                      ->orWhere('status', 'like', '%Bakım%')
                      ->orWhere('status', 'like', '%arıza%')
                      ->orWhere('status', 'like', '%Arıza%')
                      ->orWhere('status', 'like', '%arızalı%')
                      ->orWhere('status', 'like', '%Arızalı%')
                      ->orWhere('status', '=', 'Bakım Gerekiyor')
                      ->orWhere('status', '=', 'bakım gerekiyor')
                      ->orWhere('status', '=', 'BAKIM')
                      ->orWhere('status', '=', 'ARIZA')
                      ->orWhere('status', '=', 'ARIZALI');
            })->count()
        ];

        return view('admin.equipment.Status', compact('equipmentStocks', 'pageTitle', 'categories', 'stats'));
    }


}
