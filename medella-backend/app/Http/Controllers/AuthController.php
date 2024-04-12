<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\MedicalStaff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /*
    1 => admin
    2 => medical_staff
    3 => doctor
    4 => patient
    */

    public function me()
    {
        $user_info = User::where('id', Auth::user()->id)->first();
        // $user_info->avatar = $user_info->avatar;
        if (Auth::user()->hasRole('admin')) {
            $department = DB::table('users')->join('departments','departments.id','=','users.department_id')
            ->where('users.id',$user_info->id)
            ->first([
                'departments.id as department_id',
                'departments.name as department_name',
            ]);
            $user_info->department = $department;
        }
        elseif (Auth::user()->hasRole('patient')) {

        }
        elseif(Auth::user()->hasRole('doctor')){
            $doctor = DB::table('users')->join('doctors','doctors.user_id','=','users.id')
            ->join('clinics','clinics.id','=','doctors.clinic_id')
            ->join('departments','departments.id','=','users.department_id')
            ->where('users.id',Auth::user()->id)
            ->first([
                'doctors.id as doctor_id',
                'doctors.name as doctor_name',
                'clinics.id as clinic_id',
                'clinics.name as clinic_name',
                'departments.name as department_name'
            ]);
            $user_info->doctor = $doctor;

        }
        elseif (Auth::user()->hasRole('medical_staff')) {
            $medical_staff = DB::table('users')
            ->join('medical_staff','medical_staff.user_id','=','users.id')
            ->join('clinics','clinics.id','=','medical_staff.clinic_id')
            ->join('departments','departments.id','=','users.department_id')
            ->where('users.id',Auth::user()->id)
            ->first([
                'medical_staff.id as medical_staff_id',
                'medical_staff.name as medical_staff_name',
                'clinics.id as clinic_id',
                'clinics.name as clinic_name',
                'departments.name as department_name'
            ]);
            $user_info->medical_staff = $medical_staff;
        }

        return $this->sendResponse($user_info, "User Retrieved Successfully", 200);
    }

    public function register(Request $request)
    {


        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:8',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'gender' => 'required',
            'role_id' => 'required',
        ]);
        if ($request->role_id == 1) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'phone' => $request->phone,
                'city' => $request->city,
                'gender' => $request->gender,
                'role_id' => $request->role_id,
                'avatar' => asset('images/blank-profile-picture-973460_960_720.webp')
            ]);
        } elseif ($request->role_id == 2) {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'city' => $request->city,
                    'gender' => $request->gender,
                    'role_id' => $request->role_id,
                    'avatar' => asset('images/blank-profile-picture-973460_960_720.webp')
                ]);
                $medical_staff_info = MedicalStaff::create([
                    'name' => $request->name,
                    'user_id' => $user->id,
                    'clinic_id' => $request->clinic_id,
                ]);
                return $user;
            });
        } elseif ($request->role_id == 3) {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'city' => $request->city,
                    'gender' => $request->gender,
                    'role_id' => $request->role_id,
                    'avatar' => asset('images/blank-profile-picture-973460_960_720.webp')
                ]);
                $doctor_info = Doctor::create([
                    'name' => $request->name,
                    'user_id' => $user->id,
                    'clinic_id' => $request->clinic_id,
                ]);
                return $user;
            });
        }
        // return response()->json($user);
        $token = $user->createToken('auth_token')->accessToken;
        $success['name'] = $user->name;
        $success['email'] = $user->email;
        $success['token'] = $token;
        return $this->sendResponse($success, "System User Registered", 200);
    }

    public function login(Request $request)
    {
        // $request->validate();
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->active_status == 0) {
                return $this->sendError('No Longer Access', ['error' => 'Forbidden'], 403);
            }
            $token = $user->createToken('auth_token')->accessToken;
            $success['token'] =  $token;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.', 200);
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->sendResponse([], 'User logout successfully.', 200);
    }
    public function changePassword(Request $request)
    {
        $request->validate(
            [
                'old' => 'required',
                'new' => 'required|min:8|string',
            ]
        );
        $user = Auth::user();
        if (!Hash::check($request->old, $user->password)) {
            return $this->sendError($user->name, "You are entering a wrong current password", 401);
        }
        $user->password = Hash::make($request->new);
        $user->save();
        return $this->sendResponse($user->name, "Password changed successfully", 200);
    }

}
