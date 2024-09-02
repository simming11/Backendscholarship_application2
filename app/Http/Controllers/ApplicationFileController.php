<?php

namespace App\Http\Controllers;

use App\Models\ApplicationFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationFileController extends Controller
{
    // Display a listing of the application files
    public function index()
    {
        $files = ApplicationFile::all();
        return response()->json($files);
    }

    // Store a newly created application file in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ApplicationID' => 'nullable|string|max:255',
            'DocumentName' => 'nullable|string|max:255',
            'DocumentType' => 'nullable|string|max:255',
            'FilePath' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        // Handle the file upload
        if ($request->hasFile('FilePath')) {
            $file = $request->file('FilePath');
            $originalFileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $originalFileName, 'public');
            $validatedData['FilePath'] = $filePath;
        }

        $applicationFile = ApplicationFile::create($validatedData);

        return response()->json($applicationFile, 201); // 201 Created
    }

    // Store a newly created external application file in the database
public function storeExternalApplicationFile(Request $request)
{
    $validatedData = $request->validate([
        'Application_EtID' => 'nullable|string|max:255',
        'DocumentName' => 'nullable|string|max:255',
        'DocumentType' => 'nullable|string|max:255',
        'FilePath' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
    ]);

    // Handle the file upload
    if ($request->hasFile('FilePath')) {
        $file = $request->file('FilePath');
        $originalFileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $originalFileName, 'public');
        $validatedData['FilePath'] = $filePath;
    }

    $applicationFile = ApplicationFile::create($validatedData);

    return response()->json($applicationFile, 201); // 201 Created
}



    // Display the specified application file
    public function show($id)
    {
        $file = ApplicationFile::findOrFail($id);
        return response()->json($file);
    }

    // Display application files related to a specific application using relationships
    public function showFilesByType($id, $type)
    {
        $files = ApplicationFile::where('ApplicationID', $id)
            ->where('DocumentType', $type)
            ->get();

        if ($files->isEmpty()) {
            return response()->json(['message' => "No files found for type: $type"], 404);
        }

        return response()->json($files);
    }

// Update the specified application files in the database
public function update(Request $request, $id)
{
    // Validate the incoming request data
    $validatedDataArray = $request->validate([
        'ApplicationID' => 'nullable|string|max:255',
        'FileTypes' => 'required|array', // Validate that FileTypes is an array
        'FileTypes.*' => 'string|max:255', // Each FileType should be a string
        'FilePaths' => 'required|array', // Validate that FilePaths is an array
        'FilePaths.*' => 'file|mimes:pdf,doc,docx,jpg,png|max:2048', // Ensure each file is uploaded and validated
        'DocumentNames' => 'nullable|array', // Allow document names for each file
        'DocumentNames.*' => 'string|max:255',
    ]);

    // Retrieve the ApplicationID, default to the $id passed in URL if not provided in the request
    $applicationID = $validatedDataArray['ApplicationID'] ?? $id;

    // Retrieve all existing files related to the ApplicationID
    $existingFiles = ApplicationFile::where('ApplicationID', $applicationID)->get();

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
    foreach ($validatedDataArray['FilePaths'] as $index => $newFile) {
        // Store the new file
        $originalFileName = $newFile->getClientOriginalName();
        $filePath = $newFile->storeAs('uploads', $originalFileName, 'public');

        // Prepare data for creating a new file entry
        $fileData = [
            'ApplicationID' => $applicationID,
            'DocumentType' => $validatedDataArray['FileTypes'][$index] ?? '',
            'FilePath' => $filePath,
            'DocumentName' => $validatedDataArray['DocumentNames'][$index] ?? null,
        ];

        // Create a new file entry in the database
        ApplicationFile::create($fileData);
    }

    return response()->json(['message' => 'Files updated successfully']);
}




    // Remove the specified application file from the database
    public function destroy($id)
    {
        $file = ApplicationFile::findOrFail($id);

        // Optionally, delete the file from storage
        if ($file->FilePath) {
            Storage::disk('public')->delete($file->FilePath);
        }

        $file->delete();

        return response()->json(null, 204); // 204 No Content
    }

    // Download the specified application file
    public function download($id)
    {
        $file = ApplicationFile::findOrFail($id);

        if (!$file || !Storage::disk('public')->exists($file->FilePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $filePath = Storage::disk('public')->path($file->FilePath);
        $originalFileName = basename($file->FilePath);

        return response()->download($filePath, $originalFileName, [
            'Content-Disposition' => 'attachment; filename="' . $originalFileName . '"',
        ]);
    }
}
