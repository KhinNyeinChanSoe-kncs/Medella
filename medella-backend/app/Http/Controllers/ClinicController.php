<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\MedicalStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClinicController extends Controller
{
    public function index(Request $request)
    {
        $clinics = Clinic::all();
        return $this->sendResponse($clinics, "Clinics Retrieved Successfully", 200);
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'description' => 'required',
        ]);
        $clinic =  Clinic::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'description' => $request->description
        ]);
        return $this->sendResponse($clinic, "Clinic Created Successfully", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $clinic = Clinic::findOrFail($id);

        return $this->sendResponse($clinic, "Clinic Found", 200);
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'description' => 'required',
        ]);
        $clinic = Clinic::findOrFail($id);
        $clinic->update($request->all());
        return $this->sendResponse($clinic, "Clinic Updated Successfully", 200);
    }
    public function searchClinics(Request $request)
    {
        $request->validate([
            'keyword' => 'required',
        ]);
        $clinics = Clinic::where('name', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('email', 'LIKE', '%' . $request->keyword . '%')
            ->get();
        return $this->sendResponse($clinics, "Clinics Retrieved Successfully", 200);
    }
    public function getStaffList(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);
        if ($request->status == 'all') {
            $staff_list = DB::table('clinics')
                ->join('medical_staff', 'medical_staff.clinic_id', '=', 'clinics.id')
                ->join('users AS medical_staff_users', 'medical_staff_users.id', '=', 'medical_staff.user_id')
                ->whereIn('medical_staff_users.role_id', [2, 3])
                ->where('clinics.id', $id)
                ->select([
                    'medical_staff_users.name as name',
                    'medical_staff_users.email as email',
                    'medical_staff_users.phone as phone',
                    'medical_staff_users.role_id as role',
                ])
                ->union(
                    DB::table('clinics')
                        ->join('doctors', 'doctors.clinic_id', '=', 'clinics.id')
                        ->join('users AS doctor_users', 'doctor_users.id', '=', 'doctors.user_id')
                        ->where('clinics.id', $id)
                        ->select([
                            'doctor_users.name as name',
                            'doctor_users.email as email',
                            'doctor_users.phone as phone',
                            'doctor_users.role_id as role',
                        ])
                )
                ->get();
            return $this->sendResponse($staff_list, "Staff List Retrieved Successfully", 200);
        } elseif ($request->status == 'doctor') {
            $staff_list = DB::table('clinics')
                ->join('doctors', 'doctors.clinic_id', '=', 'clinics.id')
                ->join('users', 'users.id', '=', 'doctors.user_id')
                ->where('clinics.id',$id)
                ->get([
                    'users.name as name',
                    'users.email as email',
                    'users.phone as phone',
                    'users.role_id as role',
                ]);
            return $this->sendResponse($staff_list, "Doctor Retrieved Successfully", 200);
        } elseif ($request->status == 'medical_staff') {
            $staff_list = DB::table('clinics')
                ->join('medical_staff', 'medical_staff.clinic_id', '=', 'clinics.id')
                ->join('users', 'users.id', '=', 'medical_staff.user_id')
                ->where('clinics.id',$id)
                ->get([

                    'users.name as name',
                    'users.email as email',
                    'users.phone as phone',
                    'users.role_id as role',
                ]);
            return $this->sendResponse($staff_list, "Medical Staff Retrieved Successfully", 200);
        }
    }
    public function getStaffInClinic(){
        $clinics = Clinic::all();
        $data = [];
        foreach($clinics as $clinic){
            $staff = MedicalStaff::where('clinic_id',$clinic->id)->count();
            $doctor = Doctor::where('clinic_id',$clinic->id)->count();
            $count = $staff+ $doctor;
            $data[$clinic->name] = $count;
        }
        // return response()->json($data);
        return $this->sendResponse($data,"Staff in Clinic",200);
    }
}
