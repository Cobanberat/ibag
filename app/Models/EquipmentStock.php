<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentStock extends Model
{
    protected $fillable = [
        'equipment_id', 'brand', 'model', 'status', 'code', 'location', 'quantity', 'feature', 'size', 'status_updated_at', 'last_used_at', 'note'
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}

