<?php

namespace App\Http\Controllers;

use App\Models\Sibling;
use Illuminate\Http\Request;

class SiblingController extends Controller
{
    // Display a listing of siblings
    public function index()
    {
        $siblings = Sibling::all();
        return response()->json($siblings);
    }

    // Store newly created siblings in the database
    public function store(Request $request)
    {
        // ตรวจสอบว่า request เป็น array หรือไม่
        $validatedDataArray = $request->validate([
            '*.ApplicationID' => 'nullable|string|max:255',
            '*.PrefixName' => 'nullable|string|max:255',
            '*.Fname' => 'nullable|string|max:255',
            '*.Lname' => 'nullable|string|max:255',
            '*.Occupation' => 'nullable|string|max:255',
            '*.EducationLevel' => 'nullable|string|max:255',
            '*.Income' => 'nullable|numeric',
            '*.Status' => 'nullable|string|max:255',
        ]);

        // สร้าง array สำหรับเก็บ siblings ที่ถูกสร้างขึ้น
        $siblings = [];

        // Loop ผ่าน array ของข้อมูลพี่น้องและสร้าง record ในฐานข้อมูล
        foreach ($validatedDataArray as $validatedData) {
            $sibling = Sibling::create($validatedData);
            $siblings[] = $sibling; // เก็บข้อมูล sibling ที่สร้างไว้ใน array
        }

        // ส่ง response กลับไปพร้อมกับข้อมูล siblings ทั้งหมดที่ถูกสร้าง
        return response()->json($siblings, 201); // 201 Created
    }

 // Display all siblings associated with the specified ApplicationID
public function show($applicationID)
{
    // Find all siblings with the given ApplicationID
    $siblings = Sibling::where('ApplicationID', $applicationID)->get();

    // Check if any siblings are found
    if ($siblings->isEmpty()) {
        return response()->json(['message' => 'No siblings found for this ApplicationID'], 404);
    }

    // Return the siblings as a JSON response
    return response()->json($siblings);
}


// Update the specified siblings in the database by ApplicationID
public function update(Request $request, $applicationID)
{
    // Validate the incoming request data
    $validatedDataArray = $request->validate([
        '*.id' => 'nullable|integer|exists:siblings,id',
        '*.PrefixName' => 'nullable|string|max:255',
        '*.Fname' => 'nullable|string|max:255',
        '*.Lname' => 'nullable|string|max:255',
        '*.Occupation' => 'nullable|string|max:255',
        '*.EducationLevel' => 'nullable|string|max:255',
        '*.Income' => 'nullable|numeric',
        '*.Status' => 'nullable|string|max:255',
        '*.ApplicationID' => 'nullable|exists:application_internals,ApplicationID',
    ]);

    // Check if the validated data is empty
    if (empty($validatedDataArray)) {
        // If no data is sent, delete all existing siblings for this ApplicationID
        Sibling::where('ApplicationID', $applicationID)->delete();

        // Return an empty array to indicate that no siblings remain
        return response()->json([]);
    }

    // Find all existing siblings by ApplicationID
    $existingSiblings = Sibling::where('ApplicationID', $applicationID)->get();

    // Create an array to store the siblings that are updated, newly created, or to be deleted
    $updatedSiblings = [];
    $incomingIds = collect($validatedDataArray)->pluck('id')->filter()->toArray();

    // Loop through the validated data array to update or create siblings
    foreach ($validatedDataArray as $siblingData) {
        if (isset($siblingData['id'])) {
            // Find the corresponding sibling
            $sibling = $existingSiblings->firstWhere('id', $siblingData['id']);
            if ($sibling) {
                // If the sibling exists, update it
                $sibling->update($siblingData);
                $updatedSiblings[] = $sibling; // Store the updated sibling in the array
            }
        } else {
            // If the sibling does not exist (i.e., it's new), create a new one
            $siblingData['ApplicationID'] = $applicationID; // Ensure the ApplicationID is set
            $newSibling = Sibling::create($siblingData);
            $updatedSiblings[] = $newSibling; // Store the new sibling in the array
        }
    }

    // Delete any siblings that were not included in the incoming data
    $siblingsToDelete = $existingSiblings->filter(function ($sibling) use ($incomingIds) {
        return !in_array($sibling->id, $incomingIds);
    });

    foreach ($siblingsToDelete as $sibling) {
        $sibling->delete();
    }

    // Return a response with all the updated, newly created, and remaining siblings
    return response()->json($updatedSiblings);
}



    // Remove the specified sibling from the database
    public function destroy($id)
    {
        $sibling = Sibling::findOrFail($id);
        $sibling->delete();

        return response()->json(null, 204); // 204 No Content
    }
}

