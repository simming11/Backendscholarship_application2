<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    // Display a listing of guardians
    public function index()
    {
        $guardians = Guardian::all();
        return response()->json($guardians);
    }

    // Store a newly created guardian in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ApplicationID' => 'nullable|string|max:255',
            'PrefixName' => 'nullable|string|max:255',
            'FirstName' => 'nullable|string|max:255',
            'LastName' => 'nullable|string|max:255',
            'Type' => 'nullable|string|max:255',
            'Occupation' => 'nullable|string|max:255',
            'Income' => 'nullable|numeric',
            'Phone' => 'nullable|string|max:15',
            'Age' => 'nullable|integer',
            'Workplace' => 'nullable|string|max:255',
            'Status' => 'nullable|string|max:255',
        ]);

        $guardian = Guardian::create($validatedData);

        return response()->json($guardian, 201); // 201 Created
    }

// Display the specified guardian(s) by ApplicationID
public function show($applicationID)
{
    // Find guardians by ApplicationID
    $guardians = Guardian::where('ApplicationID', $applicationID)->get();

    // Check if guardians were found
    if ($guardians->isEmpty()) {
        return response()->json(['message' => 'No guardians found for the specified ApplicationID'], 404);
    }

    // Return the guardians as a JSON response
    return response()->json($guardians);
}


// Update the specified guardians in the database by ApplicationID
public function update(Request $request, $applicationID)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'guardians' => 'required|array', // Ensure 'guardians' is an array
        'guardians.*.PrefixName' => 'nullable|string|max:255',
        'guardians.*.FirstName' => 'nullable|string|max:255',
        'guardians.*.LastName' => 'nullable|string|max:255',
        'guardians.*.Type' => 'nullable|string|max:255',
        'guardians.*.Occupation' => 'nullable|string|max:255',
        'guardians.*.Income' => 'nullable|numeric',
        'guardians.*.Phone' => 'nullable|string|max:15',
        'guardians.*.Age' => 'nullable|integer',
        'guardians.*.Workplace' => 'nullable|string|max:255',
        'guardians.*.Status' => 'nullable|string|max:255',
    ]);

    // Get the array of guardians data
    $guardiansData = $validatedData['guardians'];

    // Find all guardians by ApplicationID
    $guardians = Guardian::where('ApplicationID', $applicationID)->get();

    // Loop through each guardian and update it with the corresponding data
    foreach ($guardians as $index => $guardian) {
        if (isset($guardiansData[$index])) {
            $guardian->update($guardiansData[$index]);
        }
    }

    // Return the updated guardians as a JSON response
    return response()->json($guardians);
}


    // Remove the specified guardian from the database
    public function destroy($id)
    {
        $guardian = Guardian::findOrFail($id);
        $guardian->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
