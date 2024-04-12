<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalStaff extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id', 'clinic_id'];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function clinic()
    {
        return $this->hasOne(Clinic::class, 'id', 'clinic_id');
    }
}
