<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    // Display a listing of the activities
    public function index()
    {
        $activities = Activity::all();
        return response()->json($activities);
    }

// Store newly created activities in the database
public function store(Request $request)
{
    // ตรวจสอบว่า request เป็น array หรือไม่ และทำการ validate ข้อมูลแต่ละรายการ
    $validatedDataArray = $request->validate([
        '*.AcademicYear' => 'nullable|string|max:255',
        '*.ActivityName' => 'nullable|string|max:255',
        '*.Position' => 'nullable|string|max:255',
        '*.ApplicationID' => 'nullable|exists:application_internals,ApplicationID',
    ]);

    // สร้าง array สำหรับเก็บ activities ที่ถูกสร้างขึ้น
    $activities = [];

    // Loop ผ่าน array ของข้อมูลกิจกรรมและสร้าง record ในฐานข้อมูล
    foreach ($validatedDataArray as $validatedData) {
        $activity = Activity::create($validatedData);
        $activities[] = $activity; // เก็บข้อมูล activity ที่สร้างไว้ใน array
    }

    // ส่ง response กลับไปพร้อมกับข้อมูล activities ทั้งหมดที่ถูกสร้าง
    return response()->json($activities, 201); // 201 Created
}

    // Display the specified activity
    public function show($id)
    {
        $activity = Activity::findOrFail($id);
        return response()->json($activity);
    }

// Update the specified activities in the database by ApplicationID
public function update(Request $request, $applicationID)
{
    // Validate the incoming request data
    $validatedDataArray = $request->validate([
        '*.id' => 'nullable|integer|exists:activities,id',
        '*.AcademicYear' => 'nullable|string|max:255',
        '*.ActivityName' => 'nullable|string|max:255',
        '*.Position' => 'nullable|string|max:255',
        '*.ApplicationID' => 'nullable|exists:application_internals,ApplicationID',
    ]);

    // Check if the validated data is empty
    if (empty($validatedDataArray)) {
        // If no data is sent, delete all existing activities for this ApplicationID
        Activity::where('ApplicationID', $applicationID)->delete();

        // Return an empty array to indicate that no activities remain
        return response()->json([]);
    }

    // Find all existing activities by ApplicationID
    $existingActivities = Activity::where('ApplicationID', $applicationID)->get();

    // Create an array to store the activities that are updated, newly created, or to be deleted
    $updatedActivities = [];
    $incomingIds = collect($validatedDataArray)->pluck('id')->filter()->toArray();

    // Loop through the validated data array to update or create activities
    foreach ($validatedDataArray as $activityData) {
        if (isset($activityData['id'])) {
            // Find the corresponding activity
            $activity = $existingActivities->firstWhere('id', $activityData['id']);
            if ($activity) {
                // If the activity exists, update it
                $activity->update($activityData);
                $updatedActivities[] = $activity; // Store the updated activity in the array
            }
        } else {
            // If the activity does not exist (i.e., it's new), create a new one
            $activityData['ApplicationID'] = $applicationID; // Ensure the ApplicationID is set
            $newActivity = Activity::create($activityData);
            $updatedActivities[] = $newActivity; // Store the new activity in the array
        }
    }

    // Delete any activities that were not included in the incoming data
    $activitiesToDelete = $existingActivities->filter(function ($activity) use ($incomingIds) {
        return !in_array($activity->id, $incomingIds);
    });

    foreach ($activitiesToDelete as $activity) {
        $activity->delete();
    }

    // Return a response with all the updated, newly created, and remaining activities
    return response()->json($updatedActivities);
}




    // Remove the specified activity from the database
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
