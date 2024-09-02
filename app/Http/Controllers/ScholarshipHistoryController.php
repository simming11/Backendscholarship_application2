<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipHistory;
use Illuminate\Http\Request;

class ScholarshipHistoryController extends Controller
{
    // Display a listing of scholarship histories
    public function index()
    {
        $histories = ScholarshipHistory::all();
        return response()->json($histories);
    }

    public function store(Request $request)
    {
        // Validate that the request is an array and contains the specified fields without requiring them
        $validatedDataArray = $request->validate([
            '*.ApplicationID' => 'nullable|string|max:255',
            '*.ScholarshipName' => 'nullable|string|max:255',
            '*.AmountReceived' => 'nullable|numeric',
            '*.AcademicYear' => 'nullable|string|max:255',
        ]);
    
        // Create an array to store the scholarship histories that are created
        $scholarshipHistories = [];
    
        // Loop through the array of scholarship history data and create records in the database
        foreach ($validatedDataArray as $validatedData) {
            $history = ScholarshipHistory::create($validatedData);
            $scholarshipHistories[] = $history; // Store the created scholarship history in the array
        }
    
        // Return a response with all the created scholarship histories
        return response()->json($scholarshipHistories, 201); // 201 Created
    }
    
    // Display the specified scholarship history
    public function show($id)
    {
        $history = ScholarshipHistory::findOrFail($id);
        return response()->json($history);
    }

// Update the specified scholarship histories in the database by ApplicationID
public function update(Request $request, $applicationID)
{
    // Validate the incoming request data
    $validatedDataArray = $request->validate([
        '*.id' => 'nullable|integer|exists:scholarship_histories,id',
        '*.ScholarshipName' => 'nullable|string|max:255',
        '*.AmountReceived' => 'nullable|numeric',
        '*.AcademicYear' => 'nullable|string|max:255',
        '*.ApplicationID' => 'nullable|exists:application_internals,ApplicationID',
    ]);

    // Check if the validated data is empty
    if (empty($validatedDataArray)) {
        // If no data is sent, delete all existing scholarship histories for this ApplicationID
        ScholarshipHistory::where('ApplicationID', $applicationID)->delete();

        // Return an empty array to indicate that no scholarship histories remain
        return response()->json([]);
    }

    // Find all existing scholarship histories by ApplicationID
    $existingScholarshipHistories = ScholarshipHistory::where('ApplicationID', $applicationID)->get();

    // Create an array to store the scholarship histories that are updated, newly created, or to be deleted
    $updatedScholarshipHistories = [];
    $incomingIds = collect($validatedDataArray)->pluck('id')->filter()->toArray();

    // Loop through the validated data array to update or create scholarship histories
    foreach ($validatedDataArray as $scholarshipData) {
        if (isset($scholarshipData['id'])) {
            // Find the corresponding scholarship history
            $scholarshipHistory = $existingScholarshipHistories->firstWhere('id', $scholarshipData['id']);
            if ($scholarshipHistory) {
                // If the scholarship history exists, update it
                $scholarshipHistory->update($scholarshipData);
                $updatedScholarshipHistories[] = $scholarshipHistory; // Store the updated scholarship history in the array
            }
        } else {
            // If the scholarship history does not exist (i.e., it's new), create a new one
            $scholarshipData['ApplicationID'] = $applicationID; // Ensure the ApplicationID is set
            $newScholarshipHistory = ScholarshipHistory::create($scholarshipData);
            $updatedScholarshipHistories[] = $newScholarshipHistory; // Store the new scholarship history in the array
        }
    }

    // Delete any scholarship histories that were not included in the incoming data
    $scholarshipHistoriesToDelete = $existingScholarshipHistories->filter(function ($scholarshipHistory) use ($incomingIds) {
        return !in_array($scholarshipHistory->id, $incomingIds);
    });

    foreach ($scholarshipHistoriesToDelete as $scholarshipHistory) {
        $scholarshipHistory->delete();
    }

    // Return a response with all the updated, newly created, and remaining scholarship histories
    return response()->json($updatedScholarshipHistories);
}




    // Remove the specified scholarship history from the database
    public function destroy($id)
    {
        $history = ScholarshipHistory::findOrFail($id);
        $history->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
