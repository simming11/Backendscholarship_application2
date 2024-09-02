<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipType;
use Illuminate\Http\Request;

class ScholarshipTypeController extends Controller
{
    // Display a listing of scholarship types
    public function index()
    {
        $types = ScholarshipType::all();
        return response()->json($types);
    }

    // Store a newly created scholarship type in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'TypeName' => 'required|string|max:255',
        ]);

        $type = ScholarshipType::create($validatedData);

        return response()->json($type, 201); // 201 Created
    }

    // Display the specified scholarship type
    public function show($id)
    {
        $type = ScholarshipType::findOrFail($id);
        return response()->json($type);
    }

    // Update the specified scholarship type in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'TypeName' => 'sometimes|required|string|max:255',
        ]);

        $type = ScholarshipType::findOrFail($id);
        $type->update($validatedData);

        return response()->json($type);
    }

    // Remove the specified scholarship type from the database
    public function destroy($id)
    {
        $type = ScholarshipType::findOrFail($id);
        $type->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
