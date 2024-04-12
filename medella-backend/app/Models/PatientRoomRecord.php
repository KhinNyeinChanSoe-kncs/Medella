<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientRoomRecord extends Model
{
    use HasFactory;
    protected $fillable = ['room_id', 'patient_id', 'ipd_start_date', 'ipd_end_date'];
    public function patient()
    {
        return $this->hasOne(Patient::class, 'id', 'patient_id');
    }
    public function room()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
}
