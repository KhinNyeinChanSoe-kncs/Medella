<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicalStaff;
use App\Models\Patient;
use App\Models\PatientRoomRecord;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientRoomRecordController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {

            $patientRoomRecords = DB::table('patient_room_records')
                ->join('patients', 'patients.id', '=', 'patient_room_records.patient_id')
                ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
                ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                ->join('rooms', 'rooms.id', '=', 'patient_room_records.room_id')
                ->select(
                    DB::raw('DISTINCT patients.id as patient_id,
                     patients.name as patient_name,
                      doctors.id as doctor_id,
                      doctors.name as doctor_name,
                      rooms.room_number as room_number,
                      patients.inpatient,
                      patient_room_records.ipd_start_date as start_date,
                     patient_room_records.ipd_end_date as end_date')
                )
                // ->orderBy('start_date', 'desc')

                ->get();
            return $this->sendResponse($patientRoomRecords, "PatientRoomRecords Data Retrieved", 200);
        } elseif (Auth::user()->hasRole('medical_staff')) {

            $clinic_id = DB::table('medical_staff')
                ->join('clinics', 'clinics.id', '=', 'medical_staff.clinic_id')
                ->join('users', 'users.id', '=', 'medical_staff.user_id')
                ->where('users.id', Auth::user()->id)
                ->first('medical_staff.clinic_id')
                ->clinic_id;
            $patientRoomRecords = DB::table('patient_room_records')
                ->join('patients', 'patients.id', '=', 'patient_room_records.patient_id')
                ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
                ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                ->join('clinics', 'clinics.id', '=', 'doctors.clinic_id')
                ->join('medical_staff', 'clinics.id', '=', 'medical_staff.clinic_id')
                ->join('rooms', 'rooms.id', '=', 'patient_room_records.room_id')
                ->where('doctors.clinic_id', $clinic_id)
                ->select(DB::raw('DISTINCT patients.id as patient_id,
                 patients.name as patient_name,
                 doctors.id as doctor_id,
                 doctors.name as doctor_name,
                 rooms.room_number as room_number,
                 patients.inpatient,
                 patient_room_records.ipd_start_date as start_date,
                patient_room_records.ipd_end_date as end_date'))
                // ->orderBy('start_date', 'desc')

                ->get();
            return $this->sendResponse($patientRoomRecords, "Patie----ntRoomRecords Data Retrieved", 200);
        } elseif (Auth::user()->hasRole('doctor')) {
            $doctor_id = DB::table('doctors')
                ->join('users', 'users.id', '=', 'doctors.user_id')
                ->where('users.id', Auth::user()->id)
                ->first('doctors.id')
                ->id;
            $patientRoomRecords = DB::table('patient_room_records')
                ->join('rooms', 'rooms.id', '=', 'patient_room_records.room_id')
                ->join('patients', 'patients.id', '=', 'patient_room_records.patient_id')
                ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
                ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')

                ->where('doctors.id', $doctor_id)
                ->distinct()
                ->select(
                    'patients.id as patient_id',
                    'patients.name as patient_name',
                    'doctors.id as doctor_id',
                    'doctors.name as doctor_name',
                    'rooms.room_number as room_number',
                    'patients.inpatient',
                    'patient_room_records.ipd_start_date as start_date',
                    'patient_room_records.ipd_end_date as end_date'
                )
                // ->orderBy('start_date', 'desc')

                ->get();
            return $this->sendResponse($patientRoomRecords, "PatientRoomRecords Data Retrieved", 200);
        }
    }
    public function filter(Request $request)
    {
        $request->validate([
            'status' => 'required',
        ]);
        $filter_records = [];


        if ($request->status == 'all') {
            $patientRoomRecords = DB::table('patient_room_records')
                ->join('rooms', 'rooms.id', '=', 'patient_room_records.room_id')
                ->join('patients', 'patients.id', '=', 'patient_room_records.patient_id')
                ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
                ->select([
                    'patients.id as patient_id',
                    'patients.name as patient_name',
                    'rooms.room_number as room_number',
                    'patients.inpatient',
                    DB::raw('MIN(patient_room_records.ipd_start_date) as start_date'),
                    DB::raw('MAX(patient_room_records.ipd_end_date) as end_date')
                ])
                ->orderBy('patient_room_records.id','desc')
                ->groupBy('patients.id', 'patients.name',  'rooms.room_number', 'patients.inpatient')
                ->get();
            return $this->sendResponse($patientRoomRecords, "PatientRoomRecords Data Retrieved", 200);
        } else {

            $patientRoomRecords = DB::table('patient_room_records')
                ->join('patients', 'patients.id', '=', 'patient_room_records.patient_id')
                ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
                ->join('rooms', 'rooms.id', '=', 'patient_room_records.room_id')
                ->select([
                    'patients.id as patient_id',
                    'patients.name as patient_name',
                    'rooms.room_number as room_number',
                    'patients.inpatient',
                    DB::raw('MIN(patient_room_records.ipd_start_date) as start_date'),
                    DB::raw('MAX(patient_room_records.ipd_end_date) as end_date')
                ])
                ->orderBy('patient_room_records.id','desc')
                ->groupBy('patients.id', 'patients.name', 'rooms.room_number', 'patients.inpatient')
                ->get();
            foreach ($patientRoomRecords as $record) {
                if ((!Carbon::parse($record->end_date)->isPast()) || $record->end_date == null) {
                    $filter_records[] = $record;
                }
            }
            return $this->sendResponse($filter_records, "PatientRoomRecords Data Retrieved", 200);
        }
    }
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required',
            'status' => 'required',
        ]);
        $filter_records = [];
        if ($request->status == 'all') {
            // if (Auth::user()->hasRole('admin')) {

            // $patientRoomRecords = DB::table('patient_room_records')
            //     ->join('patients', 'patients.id', '=', 'patient_room_records.patient_id')
            //     ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
            //     ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
            //     ->join('rooms', 'rooms.id', '=', 'patient_room_records.room_id')
            //     ->where('patients.name', 'LIKE', '%' . $request->keyword . '%')
            //     ->select(DB::raw('DISTINCT patients.id as patient_id,
            //          patients.name as patient_name,
            //          doctors.id as doctor_id,
            //          doctors.name as doctor_name,
            //          rooms.room_number as room_number,
            //          patients.inpatient,
            //          patient_room_records.ipd_start_date as start_date,
            //         patient_room_records.ipd_end_date as end_date'))
            //     ->orderBy('start_date', 'desc')
            //     ->get();
            $patientRoomRecords = DB::table('patient_room_records')
            ->join('rooms', 'rooms.id', '=', 'patient_room_records.room_id')
            ->join('patients', 'patients.id', '=', 'patient_room_records.patient_id')
            ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
            ->where('patients.name', 'LIKE', '%' . $request->keyword . '%')
            ->select([
                'patients.id as patient_id',
                'patients.name as patient_name',
                'rooms.room_number as room_number',
                'patients.inpatient',
                DB::raw('MIN(patient_room_records.ipd_start_date) as start_date'),
                DB::raw('MAX(patient_room_records.ipd_end_date) as end_date')
            ])
            ->orderBy('patient_room_records.id','desc')
            ->groupBy('patients.id', 'patients.name',  'rooms.room_number', 'patients.inpatient')
            ->get();

            return $this->sendResponse($patientRoomRecords, "PatientRoomRecords Data Retrieved", 200);
        } else {

            $patientRoomRecords = DB::table('patient_room_records')
            ->join('rooms', 'rooms.id', '=', 'patient_room_records.room_id')
            ->join('patients', 'patients.id', '=', 'patient_room_records.patient_id')
            ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
            ->where('patients.name', 'LIKE', '%' . $request->keyword . '%')
            ->select([
                'patients.id as patient_id',
                'patients.name as patient_name',
                'rooms.room_number as room_number',
                'patients.inpatient',
                DB::raw('MIN(patient_room_records.ipd_start_date) as start_date'),
                DB::raw('MAX(patient_room_records.ipd_end_date) as end_date')
            ])
            ->orderBy('patient_room_records.id','desc')
            ->groupBy('patients.id', 'patients.name',  'rooms.room_number', 'patients.inpatient')
            ->get();
            foreach ($patientRoomRecords as $record) {
                if ((!Carbon::parse($record->end_date)->isPast()) || $record->end_date == null) {
                    $filter_records[] = $record;
                }
            }
            return $this->sendResponse($filter_records, "PatientRoomRecords Data Retrieved", 200);
        }
    }
}
