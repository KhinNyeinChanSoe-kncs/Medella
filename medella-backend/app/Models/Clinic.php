<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'phone', 'email', 'description'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'clinic_id', 'id');
    }
    public function medicalStaffs()
    {
        return $this->hasMany(MedicalStaff::class, 'clinic_id', 'id');
    }
}
