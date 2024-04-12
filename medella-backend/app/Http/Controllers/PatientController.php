<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientRoomRecord;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use stdClass;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $patients = DB::table('patients')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->where('users.active_status', 1)
            ->orderBy('users.name', 'asc')
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'users.phone as user_phone',
                'users.address as user_address',
                'patients.id as patient_id',
                'patients.inpatient as inpatient',
                'patients.parent_name as patient_parent_name',
            ])
            ->paginate(10);
        return $this->sendResponse($patients, "Patients Retrieved Successfully", 200);
    }
    public function store(Request $request)
    {
        $uploadedImage = '';
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required',
            'avatar' => 'required|mimes:jpg,jpeg,png,webp',
            'city' => 'required',
            'gender' => 'required',
            'allergies_reactions' => 'nullable',
            'surgery_histories' => 'nullable',
            'dob' => 'required',
            'emergency_contact_number' => 'required',
            'parent_name' => 'required',
            'blood_type' => 'required',
            'born_status' => 'nullable',
            'born_date' => 'nullable',
        ]);

        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            if ($image->isValid()) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $uploadedImage = $imageName;
            }
        }
        //role_id 4, avatar image, inpatient false, born_status, dead_status, born_date, dead_date, user_id
        $patient = DB::transaction(function () use ($request, $uploadedImage) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->name),
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'gender' => $request->gender,
                'role_id' => 4,
                'avatar' => $uploadedImage,
            ]);
            $patient = Patient::create([
                'inpatient' => 0,
                'allergies_reactions' => $request->allergies_reactions,
                'surgery_histories' => $request->surgery_histories,
                'dob' => $request->dob,
                'emergency_contact_number' => $request->emergency_contact_number,
                'parent_name' => $request->parent_name,
                'blood_type' => $request->blood_type,
                'born_status' => $request->born_status,
                'born_date' => $request->born_date,
                'user_id' => $user->id,
                'name' => $request->name
            ]);
            $patient_info['user'] = $user;
            $patient_info['patient'] = $patient;
            return $patient_info;
        });
        return $this->sendResponse($patient, "Patient Created Successfully", 201);
    }

    public function show($id)
    {
        // $patient = Patient::with(['user' => function ($q) {
        //     $q->where('active_status', 1);
        // }])
        //     ->where('id', $id)->first();
        $patient = DB::table('patients')->join('users', 'users.id', '=', 'patients.user_id')->where('users.active_status', 1)
            ->where('patients.id', $id)
            ->first([
                'users.id as user_id',
                'users.email as user_email',
                'users.phone as user_phone',
                'users.city as user_city',
                'users.address as user_address',
                'users.gender as user_gender',
                'users.avatar as user_avatar',
                'users.name as user_name',
                'patients.id as patient_id',
                'patients.inpatient as inpatient',
                'patients.allergies_reactions as allergies_reactions',
                // 'patients.inpatient as inpatient',
                'patients.dob as dob',
                'patients.surgery_histories as surgery_histories',
                'patients.emergency_contact_number as emergency_contact_number',
                'patients.parent_name as parent_name',
                'patients.blood_type as blood_type',
                'patients.born_status as born_status',
                'patients.born_date as born_date',
                'patients.dead_status as dead_status',
                'patients.dead_date as dead_date',
            ]);
        return $this->sendResponse($patient, "Patient Retrieved Successfully", 200);
    }
    //update function

    public function updatePersonalInformation(Request $request, $id)
    {
        $uploadedImage = '';
        $patient = Patient::findOrFail($id);
        $user = User::findOrFail($patient->user_id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'required',
            'phone' => 'required',
            'avatar' => 'nullable|mimes:jpg,jpeg,png,webp',
            'city' => 'required',
            'gender' => 'required',
            'allergies_reactions' => 'required',
            'surgery_histories' => 'required',
            'dob' => 'required',
            'emergency_contact_number' => 'required',
            'parent_name' => 'required',
            'blood_type' => 'required',
            'born_status' => 'nullable',
            'born_date' => 'nullable',
            'dead_status' => 'nullable',
            'dead_date' => 'nullable',
        ]);

        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            if ($image->isValid()) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $uploadedImage = $imageName;
            }
        }

        $updatedPatient = DB::transaction(function () use ($request, $user, $patient, $uploadedImage) {

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'gender' => $request->gender,
                'avatar' => $uploadedImage,
            ]);
            $patient->update([
                'allergies_reactions' => $request->allergies_reactions,
                'surgery_histories' => $request->surgery_histories,
                'dob' => $request->dob,
                'emergency_contact_number' => $request->emergency_contact_number,
                'parent_name' => $request->parent_name,
                'blood_type' => $request->blood_type,
                'born_status' => $request->born_status,
                'born_date' => $request->born_date,
                'dead_status' => $request->dead_status,
                'dead_date' => $request->dead_date,
                'user_id' => $user->id,
                'name' => $request->name
            ]);
            $patient_info['user'] = $user;
            $patient_info['patient'] = $patient;
            return $patient_info;
        });
        return $this->sendResponse($updatedPatient, "Patient Updated Successfully", 201);
    }

    public function patientDeactivate($id)
    {
        $patient = Patient::findOrFail($id);
        $user = DB::transaction(function () use ($patient) {
            $user = User::where('id', $patient->user_id)->first();
            $user->update([
                'active_status' => 0
            ]);
            $patient->delete();
            return $user;
        });
        return $this->sendResponse($user, "Medical Staff Deleted Successfully", 200);
    }
    public function searchPatients(Request $request)
    {
        $request->validate([
            'keyword' => 'required'
        ]);

        $patients =   $patients = DB::table('patients')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->where('users.active_status', 1)
            ->orderBy('users.name', 'asc')
            ->where('users.name', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('users.email', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('users.phone', 'LIKE', '%' . $request->keyword . '%')
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'users.phone as user_phone',
                'users.address as user_address',
                'patients.id as patient_id',
                'patients.inpatient as inpatient',
                'patients.parent_name as patient_parent_name',
            ])
            ->paginate(10);
        return $this->sendResponse($patients, "Patients Searched Successfully", 200);
    }
    //need to pass status from FE
    //default -> all
    //filter will be accomplished even in this function
    public function getBirthAndDeathList(Request $request)
    {
        $request->validate([
            'status' => 'required',
        ]);
        // $patients = '';
        if ($request->status == 'born') {

            $patients = DB::table('patients')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->where('patients.born_status', 1)
            ->where('users.active_status', 1)
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'patients.id as patient_id',
                'patients.parent_name as patient_parent_name',
                'patients.born_date as patient_born_date',
                'patients.born_status as patient_born_status',
                'patients.dead_status as patient_dead_status',
                'patients.dead_date as patient_dead_date',
            ])
            ->paginate(10);
            return $this->sendResponse($patients, "Patient B&D Retrieved", 200);
        }
        elseif ($request->status == 'death') {

            $patients = DB::table('patients')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->where('patients.dead_status', 1)
            ->where('users.active_status', 1)
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'patients.id as patient_id',
                'patients.parent_name as patient_parent_name',
                'patients.born_date as patient_born_date',
                'patients.born_status as patient_born_status',
                'patients.dead_status as patient_dead_status',
                'patients.dead_date as patient_dead_date',
            ])
            ->paginate(10);
            return $this->sendResponse($patients, "Patient B&D Retrieved", 200);
        } else {
            $patients = DB::table('patients')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->where(function ($query) {
                $query->where('patients.born_status', 1)
                      ->orWhere('patients.dead_status', 1);
            })
            ->where('users.active_status', 1)
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'patients.id as patient_id',
                'patients.parent_name as patient_parent_name',
                'patients.born_date as patient_born_date',
                'patients.born_status as patient_born_status',
                'patients.dead_status as patient_dead_status',
                'patients.dead_date as patient_dead_date',
            ])
            ->paginate(10);
            return $this->sendResponse($patients, "Patient B&D Retrieved", 200);
        }

    }
    public function getBornCounts() {

        $count = Patient::where('born_status', 1)->whereMonth('born_date',Carbon::now('m'))->count();

        return $this->sendResponse($count, "Born Count Retrieved", 200);
    }
    public function getDeathCounts() {
        $count = Patient::where('dead_status', 1)->whereMonth('dead_date',Carbon::now('m'))->count();
        return $this->sendResponse($count, "Death Count Retrieved", 200);
    }

    public function searchBornAndDeathPatients(Request $request)
    {
        $request->validate([
            'keyword' => 'required',
            'status' => 'required'
        ]);
        // $patients = Patient::whereHas('user', function ($u) use ($request) {
        //     $u->where('name', 'LIKE', '%' . $request->keyword . '%')
        //         ->orWhere('email', 'LIKE', '%' . $request->keyword . '%');
        // })
        //     ->where('born_status', 1)
        //     ->orWhere('dead_status', 1)
        //     ->with('user')
        //     ->get();
        if ($request->status == 'born') {

            $patients = DB::table('patients')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->where('patients.born_status', 1)
            ->where('users.active_status', 1)
            ->where(function($query)use($request){
                $query->where('users.name','LIKE','%'.$request->keyword.'%')
                ->orWhere('users.email','LIKE','%'.$request->keyword.'%')
                ->orWhere('users.phone','LIKE','%'.$request->keyword.'%');
            })
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'patients.id as patient_id',
                'patients.parent_name as patient_parent_name',
                'patients.born_date as patient_born_date',
                'patients.born_status as patient_born_status',
                'patients.dead_status as patient_dead_status',
                'patients.dead_date as patient_dead_date',
            ])
            ->paginate(10);
            return $this->sendResponse($patients, "Patient B&D Searched", 200);
        }
        elseif ($request->status == 'death') {

            $patients = DB::table('patients')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->where('patients.dead_status', 1)
            ->where('users.active_status', 1)
            ->where(function($query)use($request){
                $query->where('users.name','LIKE','%'.$request->keyword.'%')
                ->orWhere('users.email','LIKE','%'.$request->keyword.'%')
                ->orWhere('users.phone','LIKE','%'.$request->keyword.'%');
            })
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'patients.id as patient_id',
                'patients.parent_name as patient_parent_name',
                'patients.born_date as patient_born_date',
                'patients.born_status as patient_born_status',
                'patients.dead_status as patient_dead_status',
                'patients.dead_date as patient_dead_date',
            ])
            ->paginate(10);
            return $this->sendResponse($patients, "Patient B&D Searched", 200);
        } else {
            $patients = DB::table('patients')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->where(function ($query) {
                $query->where('patients.born_status', 1)
                      ->orWhere('patients.dead_status', 1);
            })
            ->where('users.active_status', 1)
            ->where(function($query)use($request){
                $query->where('users.name','LIKE','%'.$request->keyword.'%')
                ->orWhere('users.email','LIKE','%'.$request->keyword.'%')
                ->orWhere('users.phone','LIKE','%'.$request->keyword.'%');
            })
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'patients.id as patient_id',
                'patients.parent_name as patient_parent_name',
                'patients.born_date as patient_born_date',
                'patients.born_status as patient_born_status',
                'patients.dead_status as patient_dead_status',
                'patients.dead_date as patient_dead_date',
            ])
            ->paginate(10);
            return $this->sendResponse($patients, "Patient B&D Searched", 200);
        }
    }

    public function dischargePatient($id)
    {
        $patient = Patient::findOrFail($id);
        $current_room_record = DB::table('patient_room_records')
            ->where('patient_id', $id)
            ->whereNull('ipd_end_date')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($current_room_record) {
            DB::beginTransaction();
            try {
                $patient->update(['inpatient' => 0]);

                $record = PatientRoomRecord::findOrFail($current_room_record->id);
                $record->update(['ipd_end_date' => Carbon::now()]);

                $room = Room::findOrFail($current_room_record->room_id);
                if ($room->room_type == 'semi-private') {
                    $available_bed = $room->available_bed + 1;
                    $room->update(['available_bed' => $available_bed]);
                    if ($available_bed <= 2 && $available_bed > 0) {
                        $room->update(['status' => 1]);
                    } else {
                        $room->update(['status' => 0]);
                    }
                } else {
                    $room->update(['status' => 1, 'available_bed' => 1]);
                }

                DB::commit();
                return $this->sendResponse($patient, "Patient Discharged Successfully", 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->sendError("Failed to discharge patient. " . $e->getMessage(), 500);
            }
        } else {
            return $this->sendError("No active room record found for this patient.", 404);
        }
    }

    public function getERMofPatient($id){
        $records = DB::table('patients')
        ->join('medical_records','medical_records.patient_id','=','patients.id')
        ->join('users','patients.user_id','=','users.id')
        ->join('doctors','doctors.id','=','medical_records.doctor_id')
        ->where('users.active_status',1)
        ->where('patients.id',$id)
        ->get([
           'medical_records.id',
           'medical_records.inpatient_status',
           'medical_records.disease',
           'doctors.name as doctor_name',
        ]);
        return $this->sendResponse($records,"Patient EMR Retrieved Successfully",200);
    }
    public function getTotalCount() {
        $count = Patient::all()->count();
        return $this->sendResponse($count, "Total Patient Count", 200);
    }
}
