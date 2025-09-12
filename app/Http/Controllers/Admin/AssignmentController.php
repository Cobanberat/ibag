<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentItem;
use App\Models\Equipment;
use App\Models\EquipmentStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function create()
    {
        $pageTitle = "Yeni Zimmet";

        // Sadece kullanılabilir ekipmanları getir
        $equipmentStocks = EquipmentStock::with(['equipment.category'])
            ->whereIn('status', ['Sıfır', 'sıfır', 'Aktif', 'aktif', 'available', 'Available'])
            ->orderBy('id', 'asc')
            ->get();

        $equipments = Equipment::with('category')->orderBy('name')->get();

        return view('admin.works.index', compact('pageTitle', 'equipmentStocks', 'equipments'));
    }

    // Zimmet kaydetme

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'equipment_id' => 'required|array',
            'equipment_id.*' => 'required|exists:equipments,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'equipment_photo' => 'required|array',
            'equipment_photo.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $assignment = Assignment::create([
            'user_id' => auth()->id(),
            'note'    => $request->note,
            'status'  => '0',
        ]);

        // Aynı ekipman tekrarları: toplu takipte miktarı birleştir, ayrı takipte ayrı kayıtlar oluştur
        $grouped = [];
        foreach ($request->equipment_id as $key => $equipmentId) {
            $equipment = Equipment::find($equipmentId);
            if (! $equipment) continue;
            $qty = (int) ($request->quantity[$key] ?? 0);
            if ($equipment->individual_tracking) {
                // Ayrı takip: her biri tek adet olarak işlenecek, burada gruplanmaz
                $grouped[] = ['equipment' => $equipment, 'quantity' => max(1, $qty), 'photo_index' => $key, 'individual' => true];
            } else {
                // Toplu takip: grupla ve miktarı biriktir
                $groupedKey = 'bulk_' . $equipment->id;
                if (! isset($grouped[$groupedKey])) {
                    $grouped[$groupedKey] = ['equipment' => $equipment, 'quantity' => 0, 'photo_index' => $key, 'individual' => false];
                }
                $grouped[$groupedKey]['quantity'] += max(1, $qty);
            }
        }

        foreach ($grouped as $gk => $itemData) {
            $equipment = $itemData['equipment'];
            $quantity = (int) $itemData['quantity'];
            $photoIndex = $itemData['photo_index'];

            // Basit validation - JavaScript'te zaten stok kontrolü yapılıyor
            if ($equipment->individual_tracking && $quantity != 1) {
                return redirect()->back()
                    ->withErrors(['error' => "{$equipment->name} tek takip ekipmanıdır, sadece 1 adet alınabilir"])
                    ->withInput();
            }

            $filePath = null;

            if ($request->hasFile("equipment_photo.$photoIndex")) {
                $file = $request->file("equipment_photo.$photoIndex");

                if (! Storage::disk('public')->exists('equipment_photos')) {
                    Storage::disk('public')->makeDirectory('equipment_photos');
                }

                $filePath = $file->store('equipment_photos', 'public');
            }

            // AssignmentItem oluştur
            AssignmentItem::create([
                'assignment_id' => $assignment->id,
                'equipment_id'  => $equipment->id,
                'quantity'      => $quantity,
                'photo_path'    => $filePath,
            ]);

            // Stoktan düş
            if ($equipment->individual_tracking) {
                // Ayrı takip - durumu "Kullanımda" yap
                $stocks = EquipmentStock::where('equipment_id', $equipment->id)
                    ->whereIn('status', ['Aktif', 'active', 'Available', 'available', 'Sıfır', 'sıfır'])
                    ->limit($quantity)
                    ->get();
                foreach ($stocks as $s) {
                    $s->update(['status' => 'Kullanımda']);
                }
            } else {
                // Toplu takip - miktarı azalt
                $stock = EquipmentStock::where('equipment_id', $equipment->id)->first();
                if ($stock) {
                    $stock->quantity -= $quantity;
                    if ($stock->quantity <= 0) {
                        $stock->status = 'Yok';
                    }
                    $stock->save();
                }
            }
        }

        return redirect()->route('admin.zimmetAl')
            ->with('success', 'Zimmet alındı.');
    }

    public function myAssignments()
    {
        $assignments = Assignment::with('items.equipment')
            ->where('user_id', Auth::id())
            ->get();

        return view('admin.deliver.index', compact('assignments'));
    }

    /**
     * Zimmeti geri teslim et
     */
    public function returnAssignment(Request $request, $id)
    {
        // Validation
        $request->validate([
            'return_photos' => 'required|array',
            'return_photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'used_qty' => 'array',
            'used_qty.*' => 'integer|min:0'
        ]);

        $assignment = Assignment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        foreach ($assignment->items as $item) {
            if (isset($request->return_photos[$item->id])) {

                // Kullanıcıdan gelen kullanılan miktar bilgisi
                $usedQty = isset($request->used_qty[$item->id])
                    ? (int) $request->used_qty[$item->id]
                    : $item->quantity; // Eğer belirtilmemişse tamamını kullanmış kabul et

                // Alınan adedi aşmasın
                $usedQty = min($usedQty, $item->quantity);
                
                // Geri dönen miktar
                $returnedQty = $item->quantity - $usedQty;

                // Fotoğraf yükleme
                $file = $request->file("return_photos.{$item->id}");
                $filePath = $file->store('equipment_returns', 'public');

                // AssignmentItem tablosuna kaydet
                $item->update([
                    'return_photo_path' => $filePath,
                    'returned_quantity' => $returnedQty, // Geri dönen miktar
                ]);

                // --- Stok güncelleme ---
                if ($item->equipment->individual_tracking) {
                    // Ayrı takip - geri dönen ekipmanları "Aktif" durumuna getir
                    if ($returnedQty > 0) {
                        $stocksToReturn = EquipmentStock::where('equipment_id', $item->equipment_id)
                            ->where('status', 'Kullanımda')
                            ->limit($returnedQty)
                            ->get();
                            
                        foreach ($stocksToReturn as $stock) {
                            $stock->update(['status' => 'Aktif']);
                        }
                    }
                    
                    // Kullanılan (kayıp/hasarlı) ekipmanları "Arızalı" durumuna getir
                    if ($usedQty > $returnedQty) {
                        $damagedQty = $usedQty - $returnedQty;
                        $stocksToDamage = EquipmentStock::where('equipment_id', $item->equipment_id)
                            ->where('status', 'Kullanımda')
                            ->limit($damagedQty)
                            ->get();
                            
                        foreach ($stocksToDamage as $stock) {
                            $stock->update(['status' => 'Arızalı']);
                        }
                    }
                } else {
                    // Toplu takip - geri dönen miktarı stoka ekle
                    $stock = EquipmentStock::where('equipment_id', $item->equipment_id)->first();
                    if ($stock) {
                        $stock->quantity += $returnedQty;
                        // Stok durumunu güncelle
                        if ($stock->quantity > 0) {
                            $stock->status = 'Aktif';
                        } else {
                            $stock->status = 'Yok';
                        }
                        $stock->save();
                    }
                }
            }
        }

        // Zimmeti tamamlandı olarak işaretle
        $assignment->status = '1';
        $assignment->damage_note = $request->damage_note; // Arıza notunu kaydet
        $assignment->save();

        return redirect()->route('admin.teslimEt')->with('success', 'Zimmet başarıyla geri teslim edildi.');
    }

    public function finish($id)
    {
        // Admin kullanıcıları için user_id kontrolünü kaldırıyoruz
        $assignment = Assignment::findOrFail($id);

        $assignment->status = 1;
        $assignment->save();

        return redirect()->route('admin.gidenGelen')->with('success', 'Zimmet başarıyla tamamlandı ve teslim edilenler listesine eklendi.');
    }

    public function comingGoing()
    {
        // Status 0 → Zimmet alınanlar (bekleyen)
        $gidenAssignments = Assignment::with(['user', 'items.equipment.category'])
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // Status 1 → Teslim edilenler (tamamlanan)
        $gelenAssignments = Assignment::with(['user', 'items.equipment.category'])
            ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $pageTitle = 'Zimmet Takip Sistemi';

        return view('admin.comingGoing.index', compact('gidenAssignments', 'gelenAssignments', 'pageTitle'));
    }

    // Tek zimmet kalemi için fotoğrafları döndür
    public function itemPhotos($id)
    {
        $item = AssignmentItem::with('equipment')->find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Kayıt bulunamadı'], 404);
        }

        $initial = $item->photo_path ? asset('storage/' . $item->photo_path) : null;
        $return = $item->return_photo_path ? asset('storage/' . $item->return_photo_path) : null;
        $title = $item->equipment ? $item->equipment->name : 'Ekipman';

        return response()->json([
            'success' => true,
            'data' => [
                'title' => $title,
                'initial' => $initial,
                'return' => $return,
            ],
        ]);
    }

}
