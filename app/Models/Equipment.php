<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipments';
    protected $fillable = ['name', 'category_id', 'critical_level', 'individual_tracking', 'unit_type', 'status', 'status_note', 'responsible_user_id'];

    // Birim türleri
    const UNIT_TYPES = [
        'adet' => 'Adet',
        'metre' => 'Metre',
        'kilogram' => 'Kilogram',
        'litre' => 'Litre',
        'paket' => 'Paket',
        'kutu' => 'Kutu',
        'çift' => 'Çift',
        'takım' => 'Takım'
    ];

    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class);
    }

    public function stocks()
    {
        return $this->hasMany(EquipmentStock::class);
    }

    public function images()
    {
        return $this->hasMany(EquipmentImage::class);
    }

    public function stockQuantity()
    {
        return $this->hasOne(Stock::class);
    }

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    // Birim türü etiketini döndür
    public function getUnitTypeLabelAttribute()
    {
        if (!$this->unit_type || empty(trim($this->unit_type))) {
            return 'Adet';
        }
        return self::UNIT_TYPES[$this->unit_type] ?? 'Adet';
    }

    // Birim türü değerini güvenli şekilde döndür
    public function getSafeUnitTypeAttribute()
    {
        return $this->unit_type ?: 'adet';
    }

    // Birim türü seçeneklerini döndür
    public static function getUnitTypeOptions()
    {
        return self::UNIT_TYPES;
    }
}
