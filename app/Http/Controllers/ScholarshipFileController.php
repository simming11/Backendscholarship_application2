<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\ScholarshipFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScholarshipFileController extends Controller
{
    // Display a listing of scholarship files
    public function index()
    {
        $files = ScholarshipFile::all();
        return response()->json($files);
    }

    // Store a newly created scholarship file in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ScholarshipID' => 'required|integer|exists:scholarships,ScholarshipID',
            'FileType' => 'required|string|max:255',
            'FilePath' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:20480', // Ensure the file is uploaded and validated
            'Description' => 'nullable|string|max:255',
        ]);

        // Handle the file upload
        if ($request->hasFile('FilePath')) {
            $file = $request->file('FilePath');
            $originalFileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $originalFileName, 'public');
            $validatedData['FilePath'] = $filePath; // Save the file path to the database
        }

        $scholarshipFile = ScholarshipFile::create($validatedData);

        return response()->json($scholarshipFile, 201); // 201 Created
    }

// Display the scholarship files related to a specific scholarship using relationships
    public function show($id)
    {
        $scholarship = Scholarship::with(['files' => function ($query) {
            $query->where('FileType', 'รูปภาพ');
        }])->findOrFail($id);

        if ($scholarship->files->isEmpty()) {
            return response()->json(['message' => 'No image files found for this scholarship.'], 404);
        }

        return response()->json($scholarship->files);
    }

    // Display the scholarship files related to a specific scholarship using relationships
    public function showfilesTypeDocument($id)
    {
        $scholarship = Scholarship::with(['files' => function ($query) {
            $query->where('FileType', 'ไฟล์');
        }])->findOrFail($id);

        if ($scholarship->files->isEmpty()) {
            return response()->json(['message' => 'No document files found for this scholarship.'], 404);
        }

        return response()->json($scholarship->files);
    }

    public function showfilesTypeimages($id)
    {
        $scholarship = Scholarship::with(['files' => function ($query) {
            $query->where('FileType', 'รูปภาพ');
        }])->findOrFail($id);

        if ($scholarship->files->isEmpty()) {
            return response()->json(['message' => 'No image files found for this scholarship.'], 404);
        }

        return response()->json($scholarship->files);
    }

// Update the specified scholarship files in the database
public function update(Request $request, $id)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'ScholarshipID' => 'sometimes|required|integer|exists:scholarships,ScholarshipID',
        'FileTypes' => 'required|array', // Validate that FileTypes is an array
        'FileTypes.*' => 'string|max:255', // Each FileType should be a string
        'FilePaths' => 'required|array', // Validate that FilePaths is an array
        'FilePaths.*' => 'file|mimes:pdf,doc,docx,jpg,png|max:20480', // Ensure each file is uploaded and validated
        'Descriptions' => 'nullable|array', // Allow descriptions for each file
        'Descriptions.*' => 'string|max:255',
    ]);

    // Retrieve the ScholarshipID, default to the $id passed in URL if not provided in the request
    $scholarshipID = $validatedData['ScholarshipID'] ?? $id;

    // Retrieve all existing files related to the ScholarshipID
    $existingFiles = ScholarshipFile::where('ScholarshipID', $scholarshipID)->get();

    // Loop through each existing file to delete them from storage and the database
    foreach ($existingFiles as $existingFile) {
        // Delete the file from storage
        if (Storage::disk('public')->exists($existingFile->FilePath)) {
            Storage::disk('public')->delete($existingFile->FilePath);
        }

        // Delete the file record from the database
        $existingFile->delete();
    }

    // Loop through each provided FilePath to store the new files
    foreach ($validatedData['FilePaths'] as $index => $newFile) {
        // Store the new file
        $originalFileName = $newFile->getClientOriginalName();
        $filePath = $newFile->storeAs('uploads', $originalFileName, 'public');

        // Prepare data for creating a new file entry
        $fileData = [
            'ScholarshipID' => $scholarshipID,
            'FileType' => $validatedData['FileTypes'][$index] ?? '',
            'FilePath' => $filePath,
            'Description' => $validatedData['Descriptions'][$index] ?? null,
        ];

        // Create a new file entry in the database
        ScholarshipFile::create($fileData);
    }

    return response()->json(['message' => 'Files updated successfully']);
}






    // Remove the specified scholarship file from the database
    public function destroy($id)
    {
        $file = ScholarshipFile::findOrFail($id);

        // Optionally, delete the file from storage
        if ($file->FilePath) {
            Storage::disk('public')->delete($file->FilePath);
        }

        $file->delete();

        return response()->json(null, 204); // 204 No Content
    }

    // Download the specified scholarship file
    public function download($id)
    {
        $file = ScholarshipFile::findOrFail($id);

        if (!$file || !Storage::disk('public')->exists($file->FilePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $filePath = Storage::disk('public')->path($file->FilePath);
        $originalFileName = $file->FilePath ? basename($file->FilePath) : 'file';

        return response()->download($filePath, $originalFileName, [
            'Content-Disposition' => 'attachment; filename="' . $originalFileName . '"',
        ]);
    }

}
