<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = ['name', 'category_id'];

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
}
