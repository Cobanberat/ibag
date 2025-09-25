<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class EquipmentStock extends Model
{
    protected $table = 'stock_depo';
    
    protected $fillable = [
        'equipment_id', 'brand', 'model', 'status', 'code', 'qr_code', 'location', 'quantity', 'feature', 'size', 'status_updated_at', 'last_used_at', 'note', 'next_maintenance_date', 'photo_path'
    ];
    
    protected $dates = [
        'status_updated_at', 'last_used_at', 'next_maintenance_date', 'created_at', 'updated_at'
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
    
    // Arıza kayıtları
    public function faults()
    {
        return $this->hasMany(Fault::class, 'equipment_stock_id');
    }
    
    // Aktif arıza kaydı var mı?
    public function hasActiveFault()
    {
        return $this->faults()
            ->whereIn('status', ['Beklemede', 'İşlemde'])
            ->exists();
    }
    
    // Aktif bakım kaydı var mı?
    public function hasActiveMaintenance()
    {
        return $this->faults()
            ->where('type', 'bakım')
            ->whereIn('status', ['Beklemede', 'İşlemde'])
            ->exists();
    }
    
    // Ekipman durumu (faults tablosundan)
    public function getEquipmentStatusAttribute()
    {
        if ($this->hasActiveFault()) {
            $fault = $this->faults()
                ->whereIn('status', ['Beklemede', 'İşlemde'])
                ->orderBy('priority', 'desc')
                ->first();
            return $fault->type === 'bakım' ? 'Bakım Gerekiyor' : 'Arızalı';
        }
        return 'Aktif';
    }
    
    // Stok durumu (stok miktarına göre)
    public function getStockStatusAttribute()
    {
        // Eğer ekipman arızalı veya bakımda ise, stok durumunu göster
        if ($this->status === 'Arızalı' || $this->status === 'Bakımda') {
            return $this->status;
        }
        
        // Stok miktarına göre durum belirle
        $totalQuantity = $this->total_quantity ?? 0;
        $criticalLevel = $this->critical_level ?? 0;
        
        if ($totalQuantity <= 0) {
            return 'Tükendi';
        } elseif ($totalQuantity <= $criticalLevel) {
            return 'Az';
        } else {
            return 'Yeterli';
        }
    }

    // QR kod oluştur
    public function generateQrCode()
    {
        // Eski QR kodları zorla temizle (27 karakterlik olanlar)
        if ($this->qr_code && strlen($this->qr_code) < 100) {
            $this->qr_code = null;
            $this->save();
        }
        
        // Mevcut QR kod geçerli mi kontrol et
        if ($this->qr_code && strlen($this->qr_code) > 100) {
            return $this->qr_code;
        }

        try {
            // Basit QR kod içeriği
            $qrContent = $this->code ?? 'STOCK_' . $this->id;
            
            // QR kod oluştur (SVG formatında, imagick gerektirmez)
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(200)
                ->margin(5)
                ->generate($qrContent);

            // SVG'yi base64'e çevir
            $base64 = base64_encode($qrCode);
            
            $this->qr_code = $base64;
            $this->save();

            return $this->qr_code;
        } catch (\Exception $e) {
            \Log::error("QR kod oluşturma hatası: " . $e->getMessage());
            return null;
        }
    }

    // QR kod URL'ini döndür
    public function getQrCodeUrlAttribute()
    {
        if (!$this->qr_code) {
            $this->generateQrCode();
        }
        
        return 'data:image/png;base64,' . $this->qr_code;
    }

    // QR kod indirme linki
    public function getQrCodeDownloadUrlAttribute()
    {
        if (!$this->qr_code) {
            $this->generateQrCode();
        }
        
        return route('admin.equipment.qr-download', $this->id);
    }

    // View için gerekli accessor'lar
    public function getRowClassAttribute()
    {
        if ($this->quantity <= 0) {
            return 'table-danger';
        } elseif ($this->quantity <= ($this->equipment->critical_level ?? 3)) {
            return 'table-warning';
        }
        return '';
    }

    public function getBarClassAttribute()
    {
        if ($this->quantity <= 0) {
            return 'bg-danger';
        } elseif ($this->equipment->critical_level && $this->quantity <= $this->equipment->critical_level) {
            return 'bg-warning';
        }
        return 'bg-success';
    }

    public function getPercentageAttribute()
    {
        $criticalLevel = $this->equipment->critical_level ?? 3;
        $maxQuantity = max($criticalLevel * 2, $this->quantity);
        return min(100, ($this->quantity / $maxQuantity) * 100);
    }

    public function getStatusBadgeAttribute()
    {
        // Eğer ekipman arızalı veya bakımda ise, stok durumunu göster
        if ($this->status === 'Arızalı' || $this->status === 'Bakımda') {
            return 'fault';
        }
        
        // Stok miktarına göre durum belirle
        $totalQuantity = $this->total_quantity ?? 0;
        $criticalLevel = $this->critical_level ?? 0;
        
        if ($totalQuantity <= 0) {
            return 'empty';
        } elseif ($totalQuantity <= $criticalLevel) {
            return 'low';
        } else {
            return 'sufficient';
        }
    }

    public function getTotalQuantityAttribute()
    {
        // Eğer individual_tracking true ise, bu stok kaydının quantity'si
        // Eğer false ise, aynı code'a sahip tüm stokların toplamı
        if ($this->equipment && $this->equipment->individual_tracking) {
            return $this->quantity;
        } else {
            // Aynı code'a sahip tüm stokların toplamı
            return self::where('code', $this->code)
                ->where('equipment_id', $this->equipment_id)
                ->sum('quantity');
        }
    }

    public function getUnitTypeLabelAttribute()
    {
        return $this->equipment->unit_type_label ?? 'Adet';
    }

    public function getCriticalLevelAttribute()
    {
        return $this->equipment->critical_level ?? 3;
    }

    // Eski yapı için uyumluluk accessor'ları
    public function getNameAttribute()
    {
        return $this->equipment->name ?? null;
    }

    public function getCategoryAttribute()
    {
        return $this->equipment->category ?? null;
    }

    // Ekipman resmi için accessor
    public function getEquipmentImageAttribute()
    {
        if ($this->equipment && $this->equipment->images && $this->equipment->images->count() > 0) {
            return $this->equipment->images->first();
        }
        return null;
    }

    // Ekipman resim URL'i
    public function getEquipmentImageUrlAttribute()
    {
        $image = $this->equipment_image;
        if ($image) {
            return asset('storage/' . $image->path);
        }
        return null;
    }
}

