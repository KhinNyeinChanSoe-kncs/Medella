<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'gender',
        'city',
        'address',
        'phone',
        'role_id',
        'department_id',
        'active_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function hasRole($role)
    {
        return $this->role()->where('name', $role)->exists();
    }
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
    public function patients()
    {
        return $this->hasMany(Patient::class, 'user_id', 'id');
    }
    public function medicalStaffs()
    {
        return $this->hasMany(MedicalStaff::class, 'user_id', 'id');
    }
    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'user_id', 'id');
    }
}
