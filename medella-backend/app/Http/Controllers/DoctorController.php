<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{

    public function index()
    {
        $doctors = DB::table('doctors')
            ->join('users', 'users.id', '=', 'doctors.user_id')
            ->join('departments', 'departments.id', '=', 'users.department_id')
            ->where('users.active_status', 1)
            ->orderBy('users.name', 'asc')
            ->select([
                'users.id as id',
                'doctors.id as doctor_id',
                'users.name as name',
                'departments.name as department_name',
                'users.email as email',
                'users.phone as phone',

            ])
            ->paginate(10);
        return $this->sendResponse($doctors, "Doctors Retrieved Successfully", 200);
    }

    public function show($id)
    {

        // if (Auth::user()->id != $id  && !Auth::user()->hasRole('admin')) {
        //     return $this->sendError("You don't have permission to show this user", 403);
        // }
        $info = DB::table('doctors')
            ->join('users', 'users.id', '=', 'doctors.user_id')
            ->join('departments', 'departments.id', '=', 'users.department_id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('clinics', 'doctors.clinic_id', '=', 'clinics.id')
            ->where('users.active_status', 1)
            ->where('doctors.user_id', $id)
            ->first([
                'users.id as id',
                'doctors.id as doctor_id',
                'users.name as name',
                'departments.name as department',
                'users.email as email',
                'users.phone as phone',
                'users.address as address',
                'users.city as city',
                'users.gender as gender',
                'clinics.name as clinic',
                'roles.name as role_name',
            ]);
        return $this->sendResponse($info, "Doctor Retrieved Successfully", 200);
    }

    public function update(Request $request, $id)
    {
        $doctor =  Doctor::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'gender' => 'required',
            'clinic_id' => 'required'
        ]);
        $updated_doctor = DB::transaction(function () use ($request, $doctor, $id) {
            $user = User::where('id', $doctor->user_id)->first();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'gender' => $request->gender,
            ]);
            // $doctor_info = Doctor::where('id', $id)->first();
            $doctor->update([
                'name' => $request->name,
                'user_id' => $user->id,
                'clinic_id' => $request->clinic_id
            ]);
            $success['user'] = $user;
            $success['doctor_info'] = $doctor;
            return $success;
        });
        return $this->sendResponse($updated_doctor, "Doctor Updated Successfully", 200);
    }

    public function searchDoctors(Request $request)
    {
        $request->validate([
            'keyword' => 'required',
        ]);
        $doctors = DB::table('doctors')
            ->join('users', 'users.id', '=', 'doctors.user_id')
            ->join('departments', 'departments.id', '=', 'users.department_id')
            ->where('users.active_status', 1)
            ->where((function ($query) use ($request) {
                $query->where('users.name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('users.email', 'like', '%' . $request->keyword . '%')
                    ->orWhere('users.phone', 'like', '%' . $request->keyword . '%');
            }))
            ->orderBy('users.name', 'asc')
            ->select([
                'users.id as id',
                'users.name as name', 'departments.name as department_name',
                'users.email as email',
                'users.phone as phone',

            ])
            ->paginate(10);
        return $this->sendResponse($doctors, "Doctors Searched Successfully", 200);
    }


    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $user = DB::transaction(function () use ($doctor) {
            $user = User::where('id', $doctor->user_id)->first();
            $user->update([
                'active_status' => 0
            ]);
            $doctor->delete();
            return $user;
        });
        return $this->sendResponse($user, "Doctor Deleted Successfully", 200);
    }

    /*============================================NEED TO TEST AFTER STORING PATIENT MEDICAL RECORDS=====================================================*/
    public function getPatientofDoctor($id)
    {
        // $patients_id = MedicalRecord::where('doctor_id', $id)
        //     ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
        //     ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
        //     ->get(['patients.id as patient_id']);
        $patientLists = DB::table('medical_records')
            ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
            ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->where('users.role_id', 4)
            ->where('doctors.id', $id)
            ->distinct()
            ->get([
                'users.id as id',
                'users.name as user_name',
                'users.email as user_email',
                'users.phone as user_phone',
                'patients.id as patient_id',
                'patients.inpatient as inpatient_status',

            ]);

        // $patientLists = [];
        // foreach ($patients_id as $patient_id) {
        //     $patientLists = User::whereHas('patients', function ($patient) use ($patient_id) {
        //         $patient->where('id', $patient_id);
        //     })
        //         ->first();
        // }

        return $this->sendResponse($patientLists, "Patient Retrieved Successfully", 200);
    }
    /*==========================================================================END==================================================================*/
}
