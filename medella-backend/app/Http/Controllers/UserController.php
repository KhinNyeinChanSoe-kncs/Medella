<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\MedicalStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $users = User::where('active_status', 1)->orderBy('id', 'ASC')->paginate(10);
        return $this->sendResponse($users, "Users Data Retrieved", 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'gender' => 'required',
            'role_id' => 'required',
            'department_id' => 'required',
            // 'clinic_id' => 'required',
        ]);

        if ($request->role_id == 1) {
            //admin
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'gender' => $request->gender,
                'role_id' => $request->role_id,
                'avatar' => asset('images/blank-profile-picture-973460_960_720.webp'),
                'department_id' => $request->department_id,
                // 'clinic_id' => $request->clinic_id,
            ]);
            return $this->sendResponse($user, "Admin Account Created", 201);
        } elseif ($request->role_id == 2) {
            //medical staff
            $user = DB::transaction(function () use ($request) {
                $u = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'gender' => $request->gender,
                    'role_id' => $request->role_id,
                    'avatar' => asset('images/blank-profile-picture-973460_960_720.webp'),
                    'department_id' => $request->department_id,
                    // 'clinic_id' => $request->clinic_id,
                ]);

                $staff = MedicalStaff::create([
                    'name' => $u->name,
                    'user_id' => $u->id,
                    'clinic_id' => $request->clinic_id,
                ]);

                $success['user'] = $u;
                $success['staff'] = $staff;
                return $success;
            });
            return $this->sendResponse($user, "Medical Staff Account Created", 201);
        } elseif ($request->role_id == 3) {
            //doctor
            $user = DB::transaction(function () use ($request) {
                $u = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'gender' => $request->gender,
                    'role_id' => $request->role_id,
                    'avatar' => asset('images/blank-profile-picture-973460_960_720.webp'),
                    'department_id' => $request->department_id,
                    // 'clinic_id' => $request->clinic_id,
                ]);

                $doctor = Doctor::create([
                    'name' => $u->name,
                    'user_id' => $u->id,
                    'clinic_id' => $request->clinic_id,
                ]);

                $success['user'] = $u;
                $success['doctor'] = $doctor;
                return $success;
            });
            return $this->sendResponse($user, "Doctor Account Created Successfully", 201);
        }
    }

    public function show($id)
    {
        $user = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $id)
            ->first([
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'users.phone as user_phone',
                'users.address as user_address',
                'users.city as user_city',
                'users.gender as user_gender',
                'roles.name as role_name',
                'roles.id as role_id',

            ]);
        return $this->sendResponse($user, "User Found", 200);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'role_id' => 'required',
        ]);
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'gender' => $request->gender,
            'role_id' => $request->role_id,
        ]);
        return $this->sendResponse($user, "User Updated Successfully", 201);
    }
    public function adminAccountUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'gender' => 'required',
        ]);
        $user = User::where('id', $id)
            ->where('role_id', 1)
            ->first();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'gender' => $request->gender,
        ]);
        return $this->sendResponse($user, "Admin Account Updated", 200);
    }
    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'active_status' => 0,
        ]);
        return $this->sendResponse($user, "User Account Deactivated", 200);
    }
    public function getDeactiveAccounts()
    {
        $users = User::where('active_status', 0)->get();
        return $this->sendResponse($users, "Users Data Retrieved", 200);
    }
    public function searchActivatedAccounts(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
        ]);

        $keyword = '%' . $request->keyword . '%';

        $users = User::where(function ($query) use ($keyword) {
            $query->where('name', 'like', $keyword)
                ->orWhere('email', 'like', $keyword)
                ->orWhere('phone', 'like', $keyword);
        })
            ->where('active_status', 1)
            ->orderBy('name', 'ASC')
            ->paginate(10);

        return $this->sendResponse($users, "Users Searched Successfully", 200);
    }
    public function getAllStaff()
    {
        $staffs = DB::table('users')
            ->join('departments', 'departments.id', '=', 'users.department_id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('users.role_id', 1)
            ->orWhere('users.role_id', 2)
            ->orWhere('users.role_id', 3)
            ->select([
                'users.id as user_id',
                'users.name as name',
                'users.email as email',
                'users.phone as phone',
                'departments.name as department_name',
                'roles.name as role_name',
            ])
            ->paginate(10);
        return $this->sendResponse($staffs, "Staff Data Retrieved", 200);
    }
    public function searchStaff(Request $request)
    {
        // dd("here");
        $request->validate([
            'keyword' => 'required',
        ]);
        $staffs = DB::table('users')
        ->join('departments', 'departments.id', '=', 'users.department_id')
        ->join('roles', 'roles.id', '=', 'users.role_id')

        ->where(function ($query) use ($request) {
            $query->where('users.name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('users.email', 'like', '%' . $request->keyword . '%')
                  ->orWhere('users.phone', 'like', '%' . $request->keyword . '%');
        })
        ->whereIn('users.role_id', [1, 2, 3])
        ->select([
            'users.id as user_id',
            'users.name as name',
            'users.email as email',
            'users.phone as phone',
            'departments.name as department_name',
            'roles.name as role_name',
        ])
        ->paginate(10);



        return $this->sendResponse($staffs, "Staff Data Searched", 200);
    }
}
