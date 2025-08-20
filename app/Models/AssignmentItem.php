<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentItem extends Model
{
    protected $fillable = [
        'assignment_id',
        'equipment_id',
        'quantity',
        'photo_path',
        'return_photo_path',
        'returned_quantity'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class); // equipment tablon var
    }
}

