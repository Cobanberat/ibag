<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentImage extends Model
{
    protected $fillable = ['equipment_id', 'path', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    // Ana resim mi?
    public function isPrimary()
    {
        return $this->is_primary;
    }

    // Resim URL'ini döndür
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    // Resim boyutunu döndür (KB)
    public function getFileSizeAttribute()
    {
        if (file_exists(storage_path('app/public/' . $this->path))) {
            return round(filesize(storage_path('app/public/' . $this->path)) / 1024, 2);
        }
        return 0;
    }
}
