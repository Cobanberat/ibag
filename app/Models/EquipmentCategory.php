<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    protected $table = 'equipment_categories';
    protected $fillable = ['name', 'parent_id', 'description', 'color', 'icon'];

    public function parent()
    {
        return $this->belongsTo(EquipmentCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(EquipmentCategory::class, 'parent_id');
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class, 'category_id');
    }
}

