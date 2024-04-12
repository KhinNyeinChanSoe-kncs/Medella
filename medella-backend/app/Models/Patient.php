<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'inpatient',
        'allergies_reactions',
        'dob',
        'surgery_histories',
        'emergency_contact_number',
        'parent_name',
        'born_status',
        'dead_status',
        'born_date',
        'dead_date',
        'user_id',
        'blood_type',
        'name'
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id', 'id');
    }
    public function patientRoomRecords()
    {
        return $this->hasMany(PatientRoomRecord::class, 'patient_id', 'id');
    }
}
