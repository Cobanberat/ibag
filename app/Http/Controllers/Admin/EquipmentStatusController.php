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
        
        // Sadece arızalı veya bakım gerektiren ekipmanları çek
        $query = EquipmentStock::with(['equipment.category', 'equipment.images', 'faults' => function($q) {
            $q->whereIn('status', ['Beklemede', 'İşlemde']);
        }]);

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
            if ($request->status === 'Bakım Gerekiyor') {
                $query->whereHas('faults', function($q) {
                    $q->where('type', 'bakım')->whereIn('status', ['Beklemede', 'İşlemde']);
                });
            } elseif ($request->status === 'Arızalı') {
                $query->whereHas('faults', function($q) {
                    $q->where('type', 'arıza')->whereIn('status', ['Beklemede', 'İşlemde']);
                });
            }
        }

        // Sadece arızalı veya bakım gerektiren ekipmanları getir
        $query->whereHas('faults', function($q) {
            $q->whereIn('status', ['Beklemede', 'İşlemde']);
        });

        $equipmentStocks = $query->orderBy('updated_at', 'desc')->paginate(12)->withQueryString();

        // Kategorileri çek
        $categories = EquipmentCategory::orderBy('name')->get();

        // Durum istatistikleri - faults tablosundan
        $stats = [
            'bakim' => \App\Models\Fault::where('type', 'bakım')
                ->whereIn('status', ['Beklemede', 'İşlemde'])
                ->count(),
            'arizali' => \App\Models\Fault::where('type', 'arıza')
                ->whereIn('status', ['Beklemede', 'İşlemde'])
                ->count(),
            'toplam' => \App\Models\Fault::whereIn('status', ['Beklemede', 'İşlemde'])->count()
        ];

        return view('admin.equipment.Status', compact('equipmentStocks', 'pageTitle', 'categories', 'stats'));
    }


}
