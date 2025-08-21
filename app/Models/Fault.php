<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fault extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_stock_id',
        'reported_by',
        'type',
        'priority',
        'description',
        'photo_path',
        'status',
        'reported_date',
        'resolved_date',
        'resolution_note',
        'resolved_photo_path',
        'resolved_by'
    ];

    protected $casts = [
        'reported_date' => 'date',
        'resolved_date' => 'date',
    ];

    // İlişkiler
    public function equipmentStock()
    {
        return $this->belongsTo(EquipmentStock::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Scope'lar
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['beklemede', 'işlemde']);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'giderildi');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Accessor'lar
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'acil' => 'danger',
            'yüksek' => 'warning',
            'normal' => 'info',
            default => 'secondary'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'beklemede' => 'warning',
            'işlemde' => 'info',
            'giderildi' => 'success',
            'iptal' => 'secondary',
            default => 'secondary'
        };
    }
}
