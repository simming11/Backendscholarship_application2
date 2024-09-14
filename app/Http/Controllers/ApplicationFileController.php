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
        'FilePath' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:20480',
    ]);

    // Handle the file upload
    if ($request->hasFile('FilePath')) {
        $file = $request->file('FilePath');
        // รับเฉพาะชื่อไฟล์ต้นฉบับ
        $originalFileName = $file->getClientOriginalName();
        
        // จัดเก็บไฟล์ในโฟลเดอร์ 'public/uploads' โดยใช้ชื่อไฟล์ต้นฉบับ
        $file->storeAs('uploads', $originalFileName, 'public');
        
        // เก็บเฉพาะชื่อไฟล์ใน validatedData
        $validatedData['FilePath'] = $originalFileName;
    }

    // สร้างรายการในฐานข้อมูล
    $applicationFile = ApplicationFile::create($validatedData);

    // ส่งข้อมูลที่ถูกสร้างกลับไปพร้อมกับ HTTP status 201 Created
    return response()->json($applicationFile, 201);
}

// Store a newly created external application file in the database
public function storeExternalApplicationFile(Request $request)
{
    $validatedData = $request->validate([
        'Application_EtID' => 'nullable|string|max:255',
        'DocumentName' => 'nullable|string|max:255',
        'DocumentType' => 'nullable|string|max:255',
        'FilePath' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:20480', // Max size 20MB
    ]);

    // Handle the file upload
    if ($request->hasFile('FilePath')) {
        $file = $request->file('FilePath');
        $originalFileName = $file->getClientOriginalName();
        
        // Store the file in the 'public/uploads' directory and get the stored path
        $file->storeAs('uploads', $originalFileName, 'public');
        
        // Save only the original file name in the validatedData array
        $validatedData['FilePath'] = $originalFileName;
    }

    // Create a new entry in the application_files table
    $applicationFile = ApplicationFile::create($validatedData);

    // Return the created application file with a 201 response code
    return response()->json($applicationFile, 201);
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

    
    public function update(Request $request, $id)
    {
        $validatedDataArray = $request->validate([
            'ApplicationID' => 'nullable|string|max:255',
            'application_files' => 'nullable|array', // Expecting an array of files
            'application_files.*.DocumentType' => 'nullable|string|max:255', // Validate DocumentType
            'application_files.*.DocumentName' => 'nullable|string|max:255', // Validate DocumentName
            'application_files.*.FilePath' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:20480', // Only validate uploaded files
            'application_files.*.ExistingFilePath' => 'nullable|string|max:255', // Path for existing files
            'application_files.*.ApplicationID' => 'nullable|string|max:255', // Validate ApplicationID
        ]);
    
        $applicationID = $validatedDataArray['ApplicationID'] ?? $id;
    
        // Fetch all existing files related to the ApplicationID
        $existingFiles = ApplicationFile::where('ApplicationID', $applicationID)->get();
    
        // Collect the DocumentNames of files sent in the request
        $incomingDocumentNames = collect($validatedDataArray['application_files'])->pluck('DocumentName')->toArray();
    
        // Delete files from the database that are not in the incoming request
        foreach ($existingFiles as $existingFile) {
            if (!in_array($existingFile->DocumentName, $incomingDocumentNames)) {
                // Delete the file from storage
                if (Storage::disk('public')->exists('uploads/' . $existingFile->FilePath)) {
                    Storage::disk('public')->delete('uploads/' . $existingFile->FilePath);
                }
    
                // Delete the record from the database
                $existingFile->delete();
            }
        }
    
        // Now process the incoming files
        if (isset($validatedDataArray['application_files']) && count($validatedDataArray['application_files']) > 0) {
            foreach ($validatedDataArray['application_files'] as $index => $fileData) {
                // Check if the file already exists in the database
                $existingFile = $existingFiles->where('DocumentName', $fileData['DocumentName'])->first();
    
                $fileToSave = [
                    'ApplicationID' => $fileData['ApplicationID'] ?? $applicationID,
                    'DocumentType' => $fileData['DocumentType'] ?? '',
                    'DocumentName' => $fileData['DocumentName'] ?? null,
                ];
    
                // If a new file is uploaded
                if ($request->hasFile("application_files.{$index}.FilePath")) {
                    $file = $request->file("application_files.{$index}.FilePath");
                    $originalFileName = $file->getClientOriginalName();
    
                    // Store the new file in the 'uploads' directory
                    $file->storeAs('uploads', $originalFileName, 'public');
    
                    // Store the new file name in the database
                    $fileToSave['FilePath'] = $originalFileName;
    
                    // If the file already exists, update it
                    if ($existingFile) {
                        if (Storage::disk('public')->exists('uploads/' . $existingFile->FilePath)) {
                            Storage::disk('public')->delete('uploads/' . $existingFile->FilePath);
                        }
                        $existingFile->update($fileToSave);
                    } else {
                        ApplicationFile::create($fileToSave);
                    }
                } else {
                    // If no new file is uploaded, use the existing file path
                    $fileToSave['FilePath'] = $fileData['ExistingFilePath'];
    
                    // If an existing file record is found, update it, otherwise create a new record
                    if ($existingFile) {
                        $existingFile->update($fileToSave);
                    } else {
                        ApplicationFile::create($fileToSave);
                    }
                }
            }
        }
    
        return response()->json(['message' => 'Files updated successfully']);
    }
    
    
    
    
    
    
    



// Delete the specified application file from storage and database
public function destroy($id, $filePath)
{
    // ดึงข้อมูลไฟล์ที่ต้องการลบตาม ID
    $applicationFile = ApplicationFile::findOrFail($id);

    // ตรวจสอบว่า FilePath ที่ส่งมานั้นตรงกับข้อมูลที่มีในฐานข้อมูลหรือไม่
    if ($applicationFile->FilePath === $filePath) {
        // ตรวจสอบว่าไฟล์นั้นมีอยู่ในระบบไฟล์หรือไม่
        if (Storage::disk('public')->exists($applicationFile->FilePath)) {
            // ลบไฟล์ออกจาก storage
            Storage::disk('public')->delete($applicationFile->FilePath);
        }

        // ลบข้อมูลไฟล์ออกจากฐานข้อมูล
        $applicationFile->delete();

        return response()->json(['message' => 'File deleted successfully']);
    } else {
        // หาก FilePath ไม่ตรงกันให้ส่งข้อความแสดงข้อผิดพลาด
        return response()->json(['error' => 'File path does not match.'], 400);
    }
}


// Download the specified application file
public function download($id)
{
    // Find the file from the database using the ID
    $file = ApplicationFile::findOrFail($id);

    // Define the full path to the file within the 'uploads' directory
    $fullFilePath = 'uploads/' . $file->FilePath;

    // Check if the file exists in the storage under the 'public' disk
    if (!$file || !Storage::disk('public')->exists($fullFilePath)) {
        return response()->json(['error' => 'File not found.'], 404);
    }

    // Get the full path of the file
    $filePath = Storage::disk('public')->path($fullFilePath);
    $originalFileName = basename($file->FilePath);

    // Return the file as a downloadable response
    return response()->download($filePath, $originalFileName, [
        'Content-Disposition' => 'attachment; filename="' . $originalFileName . '"',
    ]);
}

}
