<?php

namespace App\Http\Controllers;

use App\Models\Academic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AcademicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all academics
        $academics = Academic::all();
        return response()->json($academics);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate and store new academic
        $request->validate([
            'AcademicID' => 'required|string|max:13|unique:academics,AcademicID',
            'FirstName' => 'required|string|max:30',
            'LastName' => 'required|string|max:30',
            'Position' => 'sometimes|nullable|string|max:50',
            'Email' => 'required|string|email|max:50|unique:academics,Email',
            'Phone' => 'sometimes|nullable|string|max:15',
            'Password' => 'required|string|max:50',
        ]);

        $academic = new Academic([
            'AcademicID' => $request->get('AcademicID'),
            'FirstName' => $request->get('FirstName'),
            'LastName' => $request->get('LastName'),
            'Position' => $request->get('Position'),
            'Email' => $request->get('Email'),
            'Phone' => $request->get('Phone'),
            'Password' => Hash::make($request->get('Password')), // Hash the password
        ]);

        $academic->save();

        return response()->json($academic, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Show academic by ID
        $academic = Academic::find($id);
        if ($academic) {
            return response()->json($academic);
        } else {
            return response()->json(['message' => 'Academic not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate and update academic by ID
        $request->validate([
            'AcademicID' => 'sometimes|required|string|max:13|unique:academics,AcademicID,' . $id . ',AcademicID',
            'FirstName' => 'sometimes|required|string|max:30',
            'LastName' => 'sometimes|required|string|max:30',
            'Position' => 'sometimes|nullable|string|max:50',
            'Email' => 'sometimes|required|string|email|max:50|unique:academics,Email,' . $id . ',AcademicID',
            'Phone' => 'sometimes|nullable|string|max:15',
            'Password' => 'sometimes|required|string|max:50',
        ]);

        $academic = Academic::find($id);
        if ($academic) {
            $academic->AcademicID = $request->get('AcademicID', $academic->AcademicID);
            $academic->FirstName = $request->get('FirstName', $academic->FirstName);
            $academic->LastName = $request->get('LastName', $academic->LastName);
            $academic->Position = $request->get('Position', $academic->Position);
            $academic->Email = $request->get('Email', $academic->Email);
            $academic->Phone = $request->get('Phone', $academic->Phone);
            if ($request->get('Password')) {
                $academic->Password = Hash::make($request->get('Password'));
            }

            $academic->save();

            return response()->json($academic);
        } else {
            return response()->json(['message' => 'Academic not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Delete academic by ID
        $academic = Academic::find($id);
        if ($academic) {
            $academic->delete();
            return response()->json(['message' => 'Academic deleted']);
        } else {
            return response()->json(['message' => 'Academic not found'], 404);
        }
    }
}
