<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipments';
    protected $fillable = ['name', 'category_id', 'critical_level', 'individual_tracking', 'unit_type'];

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

    // Birim türü etiketini döndür
    public function getUnitTypeLabelAttribute()
    {
        return self::UNIT_TYPES[$this->unit_type] ?? 'Adet';
    }

    // Birim türü seçeneklerini döndür
    public static function getUnitTypeOptions()
    {
        return self::UNIT_TYPES;
    }
}
