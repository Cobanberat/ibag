<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_by',
        'note',
        'status',
        'damage_note',
    ];

    // Atama ile kullanıcı ilişkisi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Zimmet alan kullanıcı
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Zimmet veren kullanıcı
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Atama ile item ilişkisi
    public function items()
    {
        return $this->hasMany(AssignmentItem::class, 'assignment_id')->with('equipment');
    }

    // Assignment ile EquipmentStock ilişkisi (eğer varsa)
    public function equipmentStock()
    {
        return $this->hasOne(EquipmentStock::class, 'assignment_id');
    }

    // Eğer gerekliyse tek ekipman ilişkisi (item üzerinden zaten erişebilirsin)
   

    // Gereksiz olabilir, Assignment kendi kendine belongsTo olamaz
    // public function assignment()
    // {
    //     return $this->belongsTo(Assignment::class, 'assignment_id');
    // }
}
