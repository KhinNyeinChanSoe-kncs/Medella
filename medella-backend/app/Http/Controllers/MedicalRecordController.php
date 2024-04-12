<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Decoders\Base64ImageDecoder;
use Intervention\Image\Decoders\DataUriImageDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Validation\Rules\Base64;
// use Intervention\Image\Laravel\Facades\Image;


class MedicalRecordController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $records = DB::table('medical_records')
                ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                ->join('users', 'patients.user_id', '=', 'users.id')
                ->where('users.active_status', 1)
                ->get(
                    [
                        'patients.id as patient_id',
                        'patients.name as patient_name',
                        'doctors.id as doctor_id',
                        'doctors.name as doctor_name',
                        'medical_records.inpatient_status',
                        'medical_records.weight',
                        'medical_records.blood_pressure',
                        'medical_records.blood_glucose',
                        'medical_records.heart_rate',
                        'medical_records.temperature',
                        'medical_records.repository_rate',
                        'medical_records.oxygen_level',

                        'medical_records.files_records',
                        'medical_records.prescription',
                        'medical_records.disease',
                        'medical_records.clinical_notes',
                    ]
                );

            return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
        }
        if (Auth::user()->hasRole('medical_staff')) {
            $records = DB::table('medical_records')
                ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                ->join('users', 'patients.user_id', '=', 'users.id')
                ->join('clinics', 'clinics.id', '=', 'doctors.clinic_id')
                ->join('medical_staff', 'clinics.id', '=', 'medical_staff.clinic_id')
                ->where('medical_staff.clinic_id', 'doctors.clinic_id')
                ->where('users.active_status', 1)
                ->get(
                    [
                        'patients.id as patient_id',
                        'patients.name as patient_name',
                        'doctors.id as doctor_id',
                        'doctors.name as doctor_name',
                        'medical_records.inpatient_status',
                        'medical_records.weight',
                        'medical_records.blood_pressure',
                        'medical_records.blood_glucose',
                        'medical_records.heart_rate',
                        'medical_records.temperature',
                        'medical_records.repository_rate',
                        'medical_records.oxygen_level',

                        'medical_records.files_records',
                        'medical_records.prescription',
                        'medical_records.disease',
                        'medical_records.clinical_notes',
                    ]
                );

            return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
        }
        if (Auth::user()->hasRole('doctor')) {
            $doctor_id = User::with('doctors')
                ->where('id', Auth::user()->id)
                ->first();
            $doctor_id = $doctor_id->doctors[0]->id;
            $records = DB::table('medical_records')
                ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                ->join('users', 'patients.user_id', '=', 'users.id')
                ->where('medical_records.doctor_id', $doctor_id)
                ->where('users.active_status', 1)
                ->get(
                    [
                        'patients.id as patient_id',
                        'patients.name as patient_name',
                        'doctors.id as doctor_id',
                        'doctors.name as doctor_name',
                        'medical_records.inpatient_status',
                        'medical_records.weight',
                        'medical_records.blood_pressure',
                        'medical_records.blood_glucose',
                        'medical_records.heart_rate',
                        'medical_records.temperature',
                        'medical_records.repository_rate',
                        'medical_records.oxygen_level',

                        'medical_records.files_records',
                        'medical_records.prescription',
                        'medical_records.disease',
                        'medical_records.clinical_notes',
                    ]
                );

            return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
        }
        if (Auth::user()->hasRole('patient')) {
            $patient_id = User::with('patients')
                ->where('id', Auth::user()->id)
                ->first();
            $patient_id = $patient_id->patients[0]->id;
            $records = DB::table('medical_records')
                ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                ->join('users', 'patients.user_id', '=', 'users.id')
                ->where('medical_records.patient_id', $patient_id)
                ->where('users.active_status', 1)
                ->get(
                    [
                        'patients.id as patient_id',
                        'patients.name as patient_name',
                        'doctors.id as doctor_id',
                        'doctors.name as doctor_name',
                        'medical_records.inpatient_status',
                        'medical_records.weight',
                        'medical_records.blood_pressure',
                        'medical_records.blood_glucose',
                        'medical_records.heart_rate',
                        'medical_records.temperature',
                        'medical_records.repository_rate',
                        'medical_records.oxygen_level',

                        'medical_records.files_records',
                        'medical_records.prescription',
                        'medical_records.disease',
                        'medical_records.clinical_notes',
                    ]
                );

            return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            //need to add from the frontend
            'inpatient_status' => 'required',
            'weight' => 'required',
            'blood_pressure' => 'required',
            'blood_glucose' => 'required',
            'heart_rate' => 'required',
            'temperature' => 'required',

            'repository_rate' => 'required',
            'oxygen_level' => 'required',
            'vital_sign' => 'nullable',
            'prescription' => 'nullable',
            'disease' => 'nullable',

            'clinical_notes' => 'nullable',
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'files_records' => 'nullable|array',
            // 'files_records.*' => 'mimes:jpeg,png,pdf|max:2048',

        ]);
        // dd($request->files_records);
        $uploadedFiles = [];
        // $request->hasFile('files_records') &&
        if (count($request->file('files_records')) > 0) {
            foreach ($request->file('files_records') as $file) {
                if ($file->isValid()) {

                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('files'), $fileName);
                    // $file->store('files');
                    $uploadedFiles[] = $fileName;
                }
            }
        }


        $record_data = [
            'weight' => $request->weight,
            'blood_pressure' => $request->blood_pressure,
            'blood_glucose' => $request->blood_glucose,
            'heart_rate' => $request->heart_rate,
            'temperature' => $request->temperature,
            'repository_rate' => $request->repository_rate,
            'oxygen_level' => $request->oxygen_level,
            'vital_sign' => $request->vital_sign,
            'prescription' => $request->prescription,
            'disease' => $request->disease,
            'clinical_notes' => $request->clinical_notes,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'inpatient_status' => $request->inpatient_status
        ];
        if (!empty($uploadedFiles)) {
            $record_data['files_records'] = implode(',', $uploadedFiles);
        }
        $record = MedicalRecord::create($record_data);
        return $this->sendResponse($record, "Medical Record Created Successfully", 201);
    }
    public function show($id)
    {
        $record = DB::table('medical_records')
            ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
            ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->where('users.active_status', 1)
            ->where('medical_records.id', $id)
            ->first(
                [
                    'patients.id as patient_id',
                    'patients.name as patient_name',
                    'doctors.id as doctor_id',
                    'doctors.name as doctor_name',
                    'medical_records.id as medical_records_id',
                    'medical_records.inpatient_status',
                    'medical_records.weight',
                    'medical_records.blood_pressure',
                    'medical_records.blood_glucose',
                    'medical_records.heart_rate',
                    'medical_records.temperature',
                    'medical_records.repository_rate',
                    'medical_records.oxygen_level',
                    'medical_records.files_records',
                    'medical_records.prescription',
                    'medical_records.disease',
                    'medical_records.clinical_notes',
                    'medical_records.created_at',
                ]
            );
        $record->created_at = Carbon::parse($record->created_at)->format('Y-m-d');
        $images = explode(',', $record->files_records);
        $record->file_records = $images;
        return $this->sendResponse($record, "Medical Record Retrieved Successfully", 200);
    }
    public function filter(Request $request)
    {
        $request->validate([
            'status' => 'required',

        ]);
        if ($request->status == 'all') {
            if (Auth::user()->hasRole('admin')) {
                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->where('users.active_status', 1)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
            if (Auth::user()->hasRole('medical_staff')) {
                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->join('clinics', 'clinics.id', '=', 'doctors.clinic_id')
                    ->join('medical_staff', 'clinics.id', '=', 'medical_staff.clinic_id')
                    ->where('medical_staff.clinic_id', 'doctors.clinic_id')
                    ->where('users.active_status', 1)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
            if (Auth::user()->hasRole('doctor')) {
                $doctor_id = User::with('doctors')
                    ->where('id', Auth::user()->id)
                    ->first();
                $doctor_id = $doctor_id->doctors[0]->id;
                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->where('medical_records.doctor_id', $doctor_id)
                    ->where('users.active_status', 1)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
            if (Auth::user()->hasRole('patient')) {
                $patient_id = User::with('patients')
                    ->where('id', Auth::user()->id)
                    ->first();
                $patient_id = $patient_id->patients[0]->id;
                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->where('medical_records.patient_id', $patient_id)
                    ->where('users.active_status', 1)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
        } elseif ($request->status == 'ipd') {
            if (Auth::user()->hasRole('admin')) {

                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->where('users.active_status', 1)
                    ->where('medical_records.inpatient_status', 1)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
            if (Auth::user()->hasRole('medical_staff')) {
                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->join('clinics', 'clinics.id', '=', 'doctors.clinic_id')
                    ->join('medical_staff', 'clinics.id', '=', 'medical_staff.clinic_id')
                    ->where('medical_staff.clinic_id', 'doctors.clinic_id')
                    ->where('users.active_status', 1)
                    ->where('medical_records.inpatient_status', 1)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
            if (Auth::user()->hasRole('doctor')) {
                $doctor_id = User::with('doctors')
                    ->where('id', Auth::user()->id)
                    ->first();
                $doctor_id = $doctor_id->doctors[0]->id;
                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->where('medical_records.doctor_id', $doctor_id)
                    ->where('users.active_status', 1)
                    ->where('medical_records.inpatient_status', 1)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
            if (Auth::user()->hasRole('patient')) {
                $patient_id = User::with('patients')
                    ->where('id', Auth::user()->id)
                    ->first();
                $patient_id = $patient_id->patients[0]->id;
                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->where('medical_records.patient_id', $patient_id)
                    ->where('users.active_status', 1)
                    ->where('medical_records.inpatient_status', 1)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
        } elseif ($request->status == 'opd') {
            if (Auth::user()->hasRole('admin')) {

                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->where('users.active_status', 1)
                    ->where('medical_records.inpatient_status', 0)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
            if (Auth::user()->hasRole('medical_staff')) {
                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->join('clinics', 'clinics.id', '=', 'doctors.clinic_id')
                    ->join('medical_staff', 'clinics.id', '=', 'medical_staff.clinic_id')
                    ->where('medical_staff.clinic_id', 'doctors.clinic_id')
                    ->where('users.active_status', 1)
                    ->where('medical_records.inpatient_status', 0)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
            if (Auth::user()->hasRole('doctor')) {
                $doctor_id = User::with('doctors')
                    ->where('id', Auth::user()->id)
                    ->first();
                $doctor_id = $doctor_id->doctors[0]->id;
                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->where('medical_records.doctor_id', $doctor_id)
                    ->where('users.active_status', 1)
                    ->where('medical_records.inpatient_status', 0)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
            if (Auth::user()->hasRole('patient')) {
                $patient_id = User::with('patients')
                    ->where('id', Auth::user()->id)
                    ->first();
                $patient_id = $patient_id->patients[0]->id;
                $records = DB::table('medical_records')
                    ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
                    ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
                    ->join('users', 'patients.user_id', '=', 'users.id')
                    ->where('medical_records.patient_id', $patient_id)
                    ->where('users.active_status', 1)
                    ->where('medical_records.inpatient_status', 0)
                    ->select(
                        [
                            'patients.id as patient_id',
                            'patients.name as patient_name',
                            'doctors.id as doctor_id',
                            'medical_records.id as medical_record_id',
                            'doctors.name as doctor_name',
                            'medical_records.inpatient_status',
                            // 'medical_records.weight',
                            // 'medical_records.blood_pressure',
                            // 'medical_records.blood_glucose',
                            // 'medical_records.heart_rate',
                            // 'medical_records.temperature',
                            // 'medical_records.repository_rate',
                            // 'medical_records.oxygen_level',
                            //
                            // 'medical_records.files_records',
                            // 'medical_records.prescription',
                            'medical_records.disease',
                            // 'medical_records.clinical_notes',
                        ]
                    )
                    ->paginate(10);

                return $this->sendResponse($records, "Medical Records Retrieved Successfully", 200);
            }
        }
    }
    public function getLatestEMR()
    {
        $emr = DB::table('users')
            ->join('patients', 'patients.user_id', '=', 'users.id')
            ->join('medical_records', 'medical_records.patient_id', '=', 'patients.id')
            ->join('doctors', 'medical_records.doctor_id', '=', 'doctors.id')
            ->where('users.id', Auth::user()->id)
            ->orderBy('medical_records.id', 'DESC')
            ->first([
                'medical_records.id',
                'medical_records.inpatient_status',
                'medical_records.weight',
                'medical_records.prescription',
                'medical_records.disease',
                'doctors.id as doctor_id',
                'doctors.name as doctor_name',
                'patients.id as patient_id',

                'patients.dob',
                'patients.allergies_reactions',
                'patients.surgery_histories',
                'patients.parent_name',
                'patients.blood_type',
                'patients.born_status'

            ]);
        return $this->sendResponse($emr, "Latest EMR Retrieved Successfully", 200);
    }
    public function getTransactionForMonth()
    {
        $currentYear = Carbon::now()->year;
        $monthlyCounts = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = MedicalRecord::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->count();
            $monthName = Carbon::create($currentYear, $month, 1)->monthName;
            $monthlyCounts[] = $count;
        }

        return $this->sendResponse($monthlyCounts, "transaction count", 200);
    }
    public function getDownloadLink($id)
    {
        // Retrieve data
        $recordData = $this->retrieveFile($id);
        $images = explode(',', $recordData->files_records);
        $renderedImage = [];

        foreach ($images as $image) {
            $img = file_get_contents(public_path('files' .DIRECTORY_SEPARATOR . $image));
            $data = base64_encode($img);
            $renderedImage[] = 'data:image/png;base64,'.$data;

        }

        $recordData->file_records = $renderedImage;

        // dd($recordData);
        $pdfContent = '
        <!doctype html>
        <html lang="en">

        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Medical Record</title>
            <style>
            .container {
                margin-left: auto;
                margin-right: auto;
                text-align: center;
            }

            table {
                margin-left: auto;
                margin-right: auto;
            }

            img {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 500px;
                height: 500px;
                object-fit: cover;
                border-radius: 10px;
                margin-top: 10px;
            }
            </style>
        </head>

        <body>
            <div class="container">
                <div style="">
                    <h2>Medical Record of ' . $recordData->patient_name . '</h2>
                </div>
                <div >
                    <div style="margin-top: 10px">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Date</td>
                                    <td>' . $recordData->created_at . '</td>
                                </tr>
                                <tr>
                                    <td>Patient ID</td>
                                    <td>' . $recordData->patient_id . '</td>
                                </tr>
                                <tr>
                                    <td>Patient</td>
                                    <td>' . $recordData->patient_name . '</td>
                                </tr>
                                <tr>
                                    <td>Doctor</td>
                                    <td>' . $recordData->doctor_name . '</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>' . ($recordData->inpatient_status === 1 ? 'Inpatient' : 'Outpatient') . '</td>
                                </tr>
                                <tr>
                                    <td>Disease</td>
                                    <td>' . $recordData->disease . '</td>
                                </tr>
                                <tr>
                                    <td>Weight</td>
                                    <td>' . $recordData->weight . '</td>
                                </tr>
                                <tr>
                                    <td>Blood Pressure</td>
                                    <td>' . $recordData->blood_pressure . '</td>
                                </tr>
                                <tr>
                                    <td>Blood Glucose</td>
                                    <td>' . $recordData->blood_glucose . '</td>
                                </tr>
                                <tr>
                                    <td>Heart Rate</td>
                                    <td>' . $recordData->heart_rate . '</td>
                                </tr>
                                <tr>
                                    <td>Repository Rate</td>
                                    <td>' . $recordData->repository_rate . '</td>
                                </tr>
                                <tr>
                                    <td>Oxygen Level</td>
                                    <td>' . $recordData->oxygen_level . '</td>
                                </tr>

                                <tr>
                                    <td>Temperature</td>
                                    <td>' . $recordData->temperature . '</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>';
        foreach ($renderedImage as $file) {
            $pdfContent .= '
                <div style="margin-top: 10px;">
                    <img src="' .  $file. '" class="rounded" style="width: 500px; height: 500px; object-fit: cover; border-radius:10px;" alt="">
                </div>';
        }
        // asset('files/' . $file)
        // dd($renderedImage);
        $pdfContent .= '
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </body>
        </html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($pdfContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        $fileName = $recordData->patient_name . '_' . $recordData->created_at.'_'.now()->format('y-m-d-m-s') . '.pdf';
        $filePath = public_path('pdfs/' . $fileName);
        file_put_contents($filePath, $pdfOutput);
        $pdfUrl = url('pdfs/' . $fileName);
        return response()->json(['pdf_url' => $pdfUrl]);
    }
    private function retrieveFile($id)
    {
        $record = DB::table('medical_records')
            ->join('patients', 'patients.id', '=', 'medical_records.patient_id')
            ->join('doctors', 'doctors.id', '=', 'medical_records.doctor_id')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->where('users.active_status', 1)
            ->where('medical_records.id', $id)
            ->first(
                [
                    'patients.id as patient_id',
                    'patients.name as patient_name',
                    'doctors.id as doctor_id',
                    'doctors.name as doctor_name',
                    'medical_records.inpatient_status',
                    'medical_records.weight',
                    'medical_records.blood_pressure',
                    'medical_records.blood_glucose',
                    'medical_records.heart_rate',
                    'medical_records.temperature',
                    'medical_records.repository_rate',
                    'medical_records.oxygen_level',
                    'medical_records.files_records',
                    'medical_records.prescription',
                    'medical_records.disease',
                    'medical_records.clinical_notes',
                    'medical_records.created_at',
                ]
            );
        $record->created_at = Carbon::parse($record->created_at)->format('Y-m-d');
        $images = explode(',', $record->files_records);
        $record->file_records = $images;
        return $record;
    }
}
