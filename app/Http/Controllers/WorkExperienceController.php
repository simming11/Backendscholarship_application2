<?php

namespace App\Http\Controllers;

use App\Models\WorkExperience;
use Illuminate\Http\Request;

class WorkExperienceController extends Controller
{
    // Display a listing of work experiences
    public function index()
    {
        $experiences = WorkExperience::all();
        return response()->json($experiences);
    }

    public function store(Request $request)
    {
        $validatedDataArray = $request->validate([
            '*.ApplicationID' => 'nullable|string|max:255',
            '*.Name' => 'nullable|string|max:255',
            '*.JobType' => 'nullable|string|max:255',
            '*.Duration' => 'nullable|string|max:255',
            '*.Earnings' => 'nullable|numeric',
        ]);

        $experiences = [];

        foreach ($validatedDataArray as $validatedData) {
            $experience = WorkExperience::create($validatedData);
            $experiences[] = $experience;
        }

        return response()->json($experiences, 201); // 201 Created
    }

    // Display the specified work experience
    public function show($id)
    {
        $experience = WorkExperience::findOrFail($id);
        return response()->json($experience);
    }

// Update the specified work experiences in the database by ApplicationID
public function update(Request $request, $applicationID)
{
    // Validate the incoming request data
    $validatedDataArray = $request->validate([
        '*.id' => 'nullable|integer|exists:work_experiences,id',
        '*.Name' => 'nullable|string|max:255',
        '*.JobType' => 'nullable|string|max:255',
        '*.Duration' => 'nullable|string|max:255',
        '*.Earnings' => 'nullable|numeric',
        '*.ApplicationID' => 'nullable|exists:application_internals,ApplicationID',
    ]);

    // Check if the validated data is empty
    if (empty($validatedDataArray)) {
        // If no data is sent, delete all existing work experiences for this ApplicationID
        WorkExperience::where('ApplicationID', $applicationID)->delete();

        // Return an empty array to indicate that no work experiences remain
        return response()->json([]);
    }

    // Find all existing work experiences by ApplicationID
    $existingExperiences = WorkExperience::where('ApplicationID', $applicationID)->get();

    // Create an array to store the work experiences that are updated, newly created, or to be deleted
    $updatedExperiences = [];
    $incomingIds = collect($validatedDataArray)->pluck('id')->filter()->toArray();

    // Loop through the validated data array to update or create work experiences
    foreach ($validatedDataArray as $experienceData) {
        if (isset($experienceData['id'])) {
            // Find the corresponding work experience
            $experience = $existingExperiences->firstWhere('id', $experienceData['id']);
            if ($experience) {
                // If the work experience exists, update it
                $experience->update($experienceData);
                $updatedExperiences[] = $experience; // Store the updated work experience in the array
            }
        } else {
            // If the work experience does not exist (i.e., it's new), create a new one
            $experienceData['ApplicationID'] = $applicationID; // Ensure the ApplicationID is set
            $newExperience = WorkExperience::create($experienceData);
            $updatedExperiences[] = $newExperience; // Store the new work experience in the array
        }
    }

    // Delete any experiences that were not included in the incoming data
    $experiencesToDelete = $existingExperiences->filter(function ($experience) use ($incomingIds) {
        return !in_array($experience->id, $incomingIds);
    });

    foreach ($experiencesToDelete as $experience) {
        $experience->delete();
    }

    // Return a response with all the updated, newly created, and remaining work experiences
    return response()->json($updatedExperiences);
}






    // Remove the specified work experience from the database
    public function destroy($id)
    {
        $experience = WorkExperience::findOrFail($id);
        $experience->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
