<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicalStaff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicalStaffController extends Controller
{
    public function index()
    {
        $m_staffs = MedicalStaff::with(['user' => function ($query) {
            $query->where('active_status', 1);
        }])
            ->orderBy('name', 'ASC')
            ->paginate(10);

        return $this->sendResponse($m_staffs, "Medical Staffs Retrieved Successfully", 200);
    }
    public function show($id)
    {
        if (Auth::user()->id != $id && Auth::user()->role_id != 1) {
            return response()->json("You don't have permission to show this user");
        }
        $m_staff = MedicalStaff::with(['user' => function ($query) {
            $query->where('active_status', 1);
        }])->findOrFail($id);
        return $this->sendResponse($m_staff, "Medical Staff Retrieved Successfully", 200);
    }
    //$id will be user_id
    public function update(Request $request, $id)
    {
        $medical_staff =  MedicalStaff::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'gender' => 'required',
            'clinic_id' => 'required'
        ]);
        $updated_m_staff = DB::transaction(function () use ($request, $medical_staff, $id) {
            $user = User::where('id', $medical_staff->user_id)->first();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'gender' => $request->gender,
            ]);
            $medical_staff->update([
                'name' => $request->name,
                'user_id' => $user->id,
                'clinic_id' => $request->clinic_id
            ]);
            $success['user'] = $user;
            $success['m_staff_info'] = $medical_staff;
            return $success;
        });
        return $this->sendResponse($updated_m_staff, "Doctor Updated Successfully", 200);
    }

    public function destroy($id)
    {
        $medical_staff = MedicalStaff::findOrFail($id);
        $user = DB::transaction(function () use ($medical_staff) {
            $user = User::where('id', $medical_staff->user_id)->first();
            $user->update([
                'active_status' => 0
            ]);
            $medical_staff->delete();
            return $user;
        });
        return $this->sendResponse($user, "Medical Staff Deleted Successfully", 200);
    }

    public function searchByMedicalStaff(Request $request)
    {
        $request->validate([
            'keyword' => 'required',
        ]);
        $m_staffs = MedicalStaff::with(['user' => function ($q) {
            $q->where('active_status', 1);
        }])
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('email', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('phone', 'LIKE', '%' . $request->keyword . '%')
            ->paginate(10);
        return $this->sendResponse($m_staffs, "Medical Staff Information Searched", 200);
    }
}
