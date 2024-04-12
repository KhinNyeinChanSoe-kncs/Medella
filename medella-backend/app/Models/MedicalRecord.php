<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'inpatient_status',
        'weight',
        'blood_pressure',
        'blood_glucose',
        'heart_rate',
        'temperature',
        'repository_rate',
        'oxygen_level',
        'files_records',
        'prescription',
        'disease',
        'clinical_notes',
        'patient_id',
        'doctor_id',
        // 'patient_room_record_id'
    ];
    public function patient()
    {
        return $this->hasOne(Patient::class, 'id', 'patient_id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id', 'doctor_id');
    }
    public function patientRoomRecord(){
        return $this->hasOne(PatientRoomRecord::class, 'id', 'patient_room_record_id');
    }

}
