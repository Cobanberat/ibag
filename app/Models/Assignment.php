<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'user_id',
        'note',
        'status',
        'damage_note',
    ];

    // Atama ile kullanıcı ilişkisi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Atama ile item ilişkisi
    public function items()
    {
        return $this->hasMany(AssignmentItem::class, 'assignment_id')->with('equipment');
    }

    // Eğer gerekliyse tek ekipman ilişkisi (item üzerinden zaten erişebilirsin)
   

    // Gereksiz olabilir, Assignment kendi kendine belongsTo olamaz
    // public function assignment()
    // {
    //     return $this->belongsTo(Assignment::class, 'assignment_id');
    // }
}
