<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_number',
        'room_type',
        'status',
        'charges',
        'available_bed',
        'floor_number',
    ];
    public function patientRoomRecords()
    {
        return $this->hasMany(PatientRoomRecord::class, 'room_id', 'id');
    }
}
