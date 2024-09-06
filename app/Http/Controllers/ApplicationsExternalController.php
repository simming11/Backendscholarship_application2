<?php

namespace App\Http\Controllers;

use App\Models\ApplicationsExternal;
use Illuminate\Http\Request;

class ApplicationsExternalController extends Controller
{
    // Display a listing of the external applications
    public function index()
    {
        $applications = ApplicationsExternal::all();
        return response()->json($applications);
    }

// Store a newly created external application in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'StudentID' => 'required|string|max:255',
            'ScholarshipID' => 'required|string|max:255',
            'Status' => 'required|string|max:255',
        ]);

        // Generate the ApplicationDate in the Thai format (Buddhist Era year)
        $currentDate = now(); // Get the current date and time
        $thaiYear = $currentDate->year + 543; // Convert Gregorian year to Buddhist Era
        $thaiDate = $currentDate->format("Y-m-d H:i:s");
        $thaiDate = str_replace($currentDate->year, $thaiYear, $thaiDate); // Replace the year with the Thai year

        $validatedData['ApplicationDate'] = $thaiDate;

        // Create the external application record
        $application = ApplicationsExternal::create($validatedData);

        return response()->json($application, 201); // 201 Created
    }

    // Display the specified external application
    public function show($id)
    {
        $application = ApplicationsExternal::findOrFail($id);
        return response()->json($application);
    }

// Display the specified external application based on StudentID
public function showByStudent($studentID)
{
    $applications = ApplicationsExternal::with([
        'student',
        'scholarship',
        'applicationFiles'
    ])
    ->where('StudentID', $studentID) // Filter by StudentID
    ->get(); // Get all results that match the StudentID

    return response()->json($applications);
}


// Get students who applied for the specified ScholarshipID
public function getStudentsByScholarshipId($scholarshipId)
{
    // Filter applications by ScholarshipID and load related student data
    $applications = ApplicationsExternal::with([
            'student',
            'scholarship',   // Load student information
        ])
        ->where('ScholarshipID', $scholarshipId) // Filter by ScholarshipID
        ->get(); // Get all results that match the ScholarshipID

    return response()->json($applications);
}

// Get specific student who applied for the specified ScholarshipID along with related data
public function getStudentByScholarshipIdAndStudentId($scholarshipId, $studentId)
{
    // Filter applications by ScholarshipID and StudentID and load related data
    $application = ApplicationsExternal::with([
            'student',               // Load student information
            'scholarship',           // Load scholarship information
            'applicationFiles',      // Load application files
        ])
        ->where('ScholarshipID', $scholarshipId) // Filter by ScholarshipID
        ->where('StudentID', $studentId) // Filter by StudentID
        ->first(); // Get the specific result that matches both

    return response()->json($application);
}



    // Update the specified external application in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'StudentID' => 'sometimes|required|string|max:255',
            'ScholarshipID' => 'sometimes|required|string|max:255',
            'Status' => 'sometimes|required|string|max:255',
            'ApplicationDate' => 'sometimes|required|date',
        ]);

        $application = ApplicationsExternal::findOrFail($id);
        $application->update($validatedData);

        return response()->json($application);
    }

    // Remove the specified external application from the database
    public function destroy($id)
    {
        $application = ApplicationsExternal::findOrFail($id);
        $application->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
