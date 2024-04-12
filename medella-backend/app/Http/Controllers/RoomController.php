<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientRoomRecord;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('id', 'asc')->get();
        return $this->sendResponse($rooms, "Rooms Data Retrieved", 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms,room_number',
            'room_type' => 'required',
            'floor_number' => 'required',
            'charges' => 'required',
        ]);

        $requestData = $request->all();

        if ($request->room_type == 'semi-private') {
            $requestData['available_bed'] = 2;
        } else {
            $requestData['available_bed'] = 1;
        }

        $room = Room::create($requestData);

        return $this->sendResponse($room, "Room Created Successfully", 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms,room_number,' . $id . ',id',
            'room_type' => 'required',
            'floor_number' => 'required',
            'charges' => 'required',
        ]);

        $room = Room::findOrFail($id);

        if ($request->room_type == 'semi-private') {
            $room->available_bed = 2;
        } else {
            $room->available_bed = 1;
        }

        $room->update($request->all());

        return $this->sendResponse($room, "Room Updated Successfully", 200);
    }


    public function show($id)
    {
        $room = Room::findOrFail($id);
        return $this->sendResponse($room, "Room Retrieved Successfully", 200);
    }
    public function getAvailabeRoom()
    {
        $rooms = Room::where('status', 1)->get();
        return $this->sendResponse($rooms, "Rooms Data Retrieved", 200);
    }
    public function getAvailabeRoomCount(){
        $count = Room::where('status', 1)->count();
        return $this->sendResponse($count,"Availabe Room Count", 200);
    }
    public function searchRoom(Request $request)
    {
        $request->validate([
            'keyword' => 'required'
        ]);
        $rooms = Room::where('room_number', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('room_type', 'LIKE', '%' . $request->keyword . '%')
            ->get();
        return $this->sendResponse($rooms, "Rooms Data Retrieved", 200);
    }
    public function reserveRoom(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required',
        ]);

        $room = Room::findOrFail($id);
        if ($room->status == 0) {
            return $this->sendError($room, "Fully Reserved", 406);
        }
        $patient = Patient::findOrFail($request->patient_id);

        $patient->update([
            'inpatient' => 1
        ]);
        if ($room->room_type == 'semi-private') {
            $reserved_record = DB::transaction(function () use ($request, $room, $id) {
                $patient_room_record = PatientRoomRecord::create([
                    'room_id' => $id,
                    'patient_id' => $request->patient_id,
                    'ipd_start_date' => Carbon::now()->format('Y-m-d'),
                ]);
                $available_bed =  --$room->available_bed;
                $room->update([
                    'available_bed' => $available_bed
                ]);
                if ($available_bed == 0) {
                    $room->update([
                        'status' => 0
                    ]);
                }
                $success['patient_room_record'] = $patient_room_record;
                $success['room'] = $room;
                return $success;
            });
            return $this->sendResponse($reserved_record, "Room Reserved Successfully", 200);
        } else {

            $reserved_record = DB::transaction(function () use ($request, $room, $id) {
                $patient_room_record = PatientRoomRecord::create([
                    'room_id' => $id,
                    'patient_id' => $request->patient_id,
                    'ipd_start_date' => Carbon::now()->format('Y-m-d'),
                ]);
                $room->update([
                    'status' => 0
                ]);
                $success['patient_room_record'] = $patient_room_record;
                $success['room'] = $room;
                return $success;
            });
            return $this->sendResponse($reserved_record, "Room Reserved Successfully", 200);
        }
    }
}
