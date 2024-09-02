<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipDocument;
use Illuminate\Http\Request;

class ScholarshipDocumentController extends Controller
{
    // Display a listing of scholarship documents
    public function index()
    {
        $documents = ScholarshipDocument::all();
        return response()->json($documents);
    }

    // Store newly created scholarship documents in the database
// Store newly created scholarship documents in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ScholarshipID' => 'required|integer|exists:scholarships,ScholarshipID',
            'documents' => 'required|array', // Validate that documents is an array
            'documents.*' => 'string|max:255', // Each document should be a string
            'otherDocument' => 'nullable|string', // Add this line
        ]);

        $documents = $validatedData['documents'];

        // Add the otherDocument to the array if it's provided
        if (!empty($validatedData['otherDocument'])) {
            $documents[] = $validatedData['otherDocument'];
        }

        foreach ($documents as $DocumentText) {
            ScholarshipDocument::create([
                'ScholarshipID' => $validatedData['ScholarshipID'],
                'DocumentText' => $DocumentText,
                'IsActive' => true, // or whatever default value you want
            ]);
        }

        return response()->json(['message' => 'Documents saved successfully!'], 201);
    }

    // Display the specified scholarship document
    public function show($id)
    {
        $document = ScholarshipDocument::findOrFail($id);
        return response()->json($document);
    }

// Update the specified scholarship documents in the database based on ScholarshipID
public function update(Request $request, $scholarshipID)
{
    $validatedData = $request->validate([
        'ScholarshipID' => 'sometimes|required|integer|exists:scholarships,ScholarshipID',
        'documents' => 'sometimes|required|array',
        'documents.*' => 'string|max:255',
        'otherDocument' => 'nullable|string', // Add this line
        'IsActive' => 'sometimes|required|boolean',
    ]);

    // Use the ScholarshipID to find documents related to this scholarship
    $documents = ScholarshipDocument::where('ScholarshipID', $scholarshipID)->get();

    // If documents array is provided, update them
    if (isset($validatedData['documents'])) {
        // Delete old documents related to this ScholarshipID
        ScholarshipDocument::where('ScholarshipID', $scholarshipID)->delete();

        $documentsToInsert = $validatedData['documents'];

        // Add the otherDocument to the array if it's provided
        if (!empty($validatedData['otherDocument'])) {
            $documentsToInsert[] = $validatedData['otherDocument'];
        }

        // Create new documents
        foreach ($documentsToInsert as $DocumentText) {
            ScholarshipDocument::create([
                'ScholarshipID' => $scholarshipID,
                'DocumentText' => $DocumentText,
                'IsActive' => $validatedData['IsActive'] ?? true, // Use IsActive from request or default to true
            ]);
        }

        return response()->json(['message' => 'Documents updated successfully!']);
    } else {
        // If only individual fields like IsActive or DocumentText are being updated
        foreach ($documents as $document) {
            $document->update($validatedData);
        }
        return response()->json(['message' => 'Documents updated successfully!']);
    }
}


    // Remove the specified scholarship document from the database
    public function destroy($id)
    {
        $document = ScholarshipDocument::findOrFail($id);
        $document->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
