<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentStock extends Model
{
    protected $table = 'stock_depo';
    
    protected $fillable = [
        'equipment_id', 'brand', 'model', 'status', 'code', 'location', 'quantity', 'feature', 'size', 'status_updated_at', 'last_used_at', 'note', 'next_maintenance_date', 'photo_path'
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
    
    // Stok durumu (sadece stok miktarı için)
    public function getStockStatusAttribute()
    {
        return $this->status; // Aktif, Kullanımda, Yok, Sıfır
    }
}

