<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicalStaffController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientRoomRecordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//public routes

Route::post('login', [AuthController::class, 'login']);


//all authenticate users
Route::middleware('auth:api')->group(function () {
//need to add to the patient middleware

    Route::post('change_password', [AuthController::class,'changePassword']);
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('users/{id}', [UserController::class, 'show']);
    Route::get('patients/{id}/emrs',[PatientController::class,'getERMofPatient']);

    Route::get('clinics/{id}', [ClinicController::class, 'show']);
    Route::get('doctors/{id}', [DoctorController::class, 'show']);
    Route::get('patients/{id}', [PatientController::class, 'show']);
    Route::get('medical-staff/{id}', [MedicalStaffController::class, 'show']);

    Route::get('/medical-records', [MedicalRecordController::class, 'index']);
    Route::get('/medical-records/{id}', [MedicalRecordController::class, 'show']);
    Route::post('/medical-records/filter', [MedicalRecordController::class, 'filter']);


    //=====================!!!!!!!!!!!Admin/Doctor Access Route !!!!!!!!!!================================
    Route::middleware(['role:admin,doctor'])->group(function () {
        //need to test only after storing medical records
        Route::get('doctors/{id}/patients', [DoctorController::class, 'getPatientofDoctor']);
    });
    //=====================!!!!!!!!!!!Admin/Patient Access Route !!!!!!!!!!================================
    Route::middleware(['role:admin,patient'])->group(function () {
        Route::get('/download/{id}',[MedicalRecordController::class,'getDownloadLink']);
    });


    //=====================!!!!!!!!!!!Patient Access Route !!!!!!!!!!================================
    Route::middleware('role:patient')->group(function () {
        Route::post('patient-password/update/{id}', [PatientController::class, 'updatePassword']);
        Route::get('/latest-emr',[MedicalRecordController::class,'getLatestEMR']);
    });

    //=====================!!!!!!!!!!! Admin/ Doctor/ Medical Staff Access Route !!!!!!!!!!================================
    Route::middleware(['role:admin,doctor,medical_staff'])->group(function () {
        Route::post('/search-doctors', [DoctorController::class, 'searchDoctors']);
        Route::post('/medical-staffs/search', [MedicalStaffController::class, 'searchByMedicalStaff']);
        Route::get('/patients', [PatientController::class, 'index']);
        Route::post('/patients', [PatientController::class, 'store']);
        Route::post('/search-patients', [PatientController::class, 'searchPatients']);
        Route::post('/bd-patients', [PatientController::class, 'searchBornAndDeathPatients']);
        Route::post('/patients/{id}/deactivate',[PatientController::class,'patientDeactivate']);
        Route::post('patients/info/update/{id}', [PatientController::class, 'updatePersonalInformation']);
        // Route::post('/patients/change-status/{id}', [PatientController::class, 'updateInpatientStatus']);

        Route::post('/medical-records', [MedicalRecordController::class, 'store']);

        Route::get('rooms/{id}', [RoomController::class, 'show']);
        Route::get('/available-rooms', [RoomController::class, 'getAvailabeRoom']);
        Route::post('search/rooms', [RoomController::class, 'searchRoom']);
        Route::post('reserve/rooms/{id}', [RoomController::class, 'reserveRoom']);
        Route::put('discharge/patients/{id}', [PatientController::class, 'dischargePatient']);


        // Route::get('/patient-room-records', [PatientRoomRecordController::class,'index']);
        Route::post('/search/patient-room-records', [PatientRoomRecordController::class, 'search']);
        Route::post('/filter/patient-room-records', [PatientRoomRecordController::class, 'filter']);
        Route::get('doctors',[DoctorController::class,'index']);
        Route::get('clinics',[ClinicController::class,'index']);
        Route::get('rooms', [RoomController::class, 'index']);
        Route::post('/clinics/{id}/staff', [ClinicController::class, 'getStaffList']);

        Route::get('/transactions',[MedicalRecordController::class,'getTransactionForMonth']);
        Route::get('/born-count',[PatientController::class,'getBornCounts']);
        Route::get('/dead-count',[PatientController::class,'getDeathCounts']);
        Route::get('/staff-in-clinic',[ClinicController::class,'getStaffInClinic']);
        Route::get('/total-patient',[PatientController::class,'getTotalCount']);
        Route::get('/available-room-count',[RoomController::class,'getAvailabeRoomCount']);
    });

    //=====================!!!!!!!!!!!Admin Access Route !!!!!!!!!!================================
    Route::middleware('role:admin')->group(function () {
        //=====================Auth Route Start  ============================
        Route::post('register', [AuthController::class, 'register']);
        //=====================Auth Route End  ============================

        //=====================User Route Start  ============================
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::post('users/delete/{id}', [UserController::class, 'deactivate']);
        Route::post('activate-users/search', [UserController::class, 'searchActivatedAccounts']);
        Route::put('users/admin/{id}', [UserController::class, 'adminAccountUpdate']);
        Route::get('staff-users', [UserController::class, 'getAllStaff']);
        Route::post('search/staff-users', [UserController::class, 'searchStaff']);
        //=====================User Route End  ============================

        //=====================Clinic Route Start  ============================
        Route::post('search-clinic', [ClinicController::class, 'searchClinics']);
        Route::apiResource('clinics', ClinicController::class)->except('show', 'destroy','index');

        //=====================Clinic Route End  ============================

        //=====================Doctor Route Start  ============================
        Route::apiResource('doctors', DoctorController::class)->except('show', 'store','index');
        //=====================Doctor Route End  ============================

        //=====================Medical Staff Route Start  ============================
        Route::apiResource('m-staffs', MedicalStaffController::class)->except('show', 'store');
        //=====================Medical Staff Route End  ============================

        //=====================Department Route Start  ============================
        Route::apiResource('departments', DepartmentController::class)->except('destroy');
        //=====================Department Route End  ============================

        //=====================Patient Route Start  ============================
        // Route::apiResource('patients', PatientController::class)->except('show', 'store', 'update','destroy');

        //=====================Patient Route End  ============================

        //=====================Room Route Start  ============================

        Route::post('rooms', [RoomController::class, 'store']);
        Route::put('rooms/{id}', [RoomController::class, 'update']);

        //=====================Room Route End  ============================

        Route::post('/born-dead/patients',[PatientController::class,'getBirthAndDeathList']);
        Route::post('/born-dead/patients/search',[PatientController::class,'searchBornAndDeathPatients']);
    });
});
