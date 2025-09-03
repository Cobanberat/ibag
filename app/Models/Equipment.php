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

    // Status sabitleri
    const STATUS_ACTIVE = 'active';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_FAULTY = 'faulty';
    const STATUS_INACTIVE = 'inactive';

    // Status seçenekleri
    const STATUS_OPTIONS = [
        self::STATUS_ACTIVE => 'Aktif',
        self::STATUS_MAINTENANCE => 'Bakım',
        self::STATUS_FAULTY => 'Arızalı',
        self::STATUS_INACTIVE => 'Pasif'
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

    // Status etiketini döndür
    public function getStatusLabelAttribute()
    {
        return self::STATUS_OPTIONS[$this->status] ?? 'Bilinmiyor';
    }

    // Status seçeneklerini döndür
    public static function getStatusOptions()
    {
        return self::STATUS_OPTIONS;
    }

    // Aktif mi?
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // Bakım gerekiyor mu?
    public function needsMaintenance()
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    // Arızalı mı?
    public function isFaulty()
    {
        return $this->status === self::STATUS_FAULTY;
    }

    // Pasif mi?
    public function isInactive()
    {
        return $this->status === self::STATUS_INACTIVE;
    }
}
