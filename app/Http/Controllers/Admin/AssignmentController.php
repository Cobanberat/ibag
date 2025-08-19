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

        $equipmentStocks = EquipmentStock::with(['equipment.category'])
            ->orderBy('id', 'asc')
            ->get();

        $equipments = Equipment::with('category')->orderBy('name')->get();

        return view('admin.works.index', compact('pageTitle', 'equipmentStocks', 'equipments'));
    }

    // Zimmet kaydetme

    public function store(Request $request)
    {
        $assignment = Assignment::create([
            'user_id' => auth()->id(),
            'note'    => $request->note,
            'status'  => '0',
        ]);

        foreach ($request->equipment_id as $key => $equipmentId) {
            $equipment = Equipment::find($equipmentId);

            $filePath = null;

            if ($request->hasFile("equipment_photo.$key")) {
                $file = $request->file("equipment_photo.$key");

                if (! Storage::disk('public')->exists('equipment_photos')) {
                    Storage::disk('public')->makeDirectory('equipment_photos');
                }

                $filePath = $file->store('equipment_photos', 'public');
            }

            AssignmentItem::create([
                'assignment_id' => $assignment->id,
                'equipment_id'  => $equipmentId,
                'photo_path'    => $filePath,
            ]);
        }

        return redirect()->route('admin.zimmetAl')
            ->with('success', 'Zimmet başarıyla oluşturuldu.');
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
        $assignment = Assignment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        foreach ($assignment->items as $item) {
            if ($item->equipment->individual_tracking && isset($request->return_photos[$item->id])) {

                $usedQty = (int) $request->used_qty[$item->id]; // kullanıcı kaç tane kullandı
                $usedQty = min($usedQty, $item->quantity);      // maksimum alınan adedi geçmesin

                // Fotoğraf yükleme
                $file     = $request->file("return_photos.$item->id");
                $filePath = $file->store('equipment_returns', 'public');

                // AssignmentItem tablosuna kaydet
                $item->update([
                    'return_photo_path' => $filePath,
                    'used_quantity'     => $usedQty,
                ]);

                // StockDepo'da status = 1 yap
                $stocks = StockDepo::where('equipment_id', $item->equipment_id)
                    ->where('status', 0) // aktif olmayanları bul
                    ->limit($usedQty)
                    ->get();

                foreach ($stocks as $stock) {
                    $stock->status = 1; // aktif
                    $stock->save();
                }
            }
        }

        $assignment->status = 1; // teslim tamamlandı
        $assignment->save();

        return redirect()->route('admin.teslimEt')->with('success', 'Zimmet başarıyla geri teslim edildi.');
    }

    public function finish($id)
    {
        $assignment = Assignment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $assignment->status = 1;
        $assignment->save();

        return redirect()->route('admin.gidenGelen')->with('success', 'Zimmet başarıyla geri teslim edildi.');
    }

    public function comingGoing()
    {
        // Status 0 → Gidenler
        $gidenAssignments = Assignment::with('user')
            ->where('status', 0)
            ->get();

        // Status 1 → Gelenler
        $gelenAssignments = Assignment::with('user')
            ->where('status', 1)
            ->get();

        $pageTitle = 'Giden-Gelen Ekipman İşlemleri';

        return view('admin.comingGoing.index', compact('gidenAssignments', 'gelenAssignments', 'pageTitle'));
    }

}
