<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return $this->sendResponse($departments, "Departments Data Retrieved", 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',

        ]);
        $department = Department::create($request->all());
        return $this->sendResponse($department, "Department Created Successfully", 201);
    }
    public function show($id)
    {
        $department = Department::findOrFail($id);
        return $this->sendResponse($department, "Department Retrieved Successfully", 200);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);
        $department = Department::findOrFail($id);
        $department->update($request->all());
        return $this->sendResponse($department, "Department Updated", 201);
    }
}
