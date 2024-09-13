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
        // Validate the incoming request data
        $validatedDataArray = $request->validate([
            'ApplicationID' => 'nullable|string|max:255',
            'application_files' => 'nullable|array', // Expecting array of files, can be empty
            'application_files.*.DocumentType' => 'nullable|string|max:255', // Validate DocumentType
            'application_files.*.DocumentName' => 'nullable|string|max:255', // Validate DocumentName
            'application_files.*.FilePath' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:20480', // Expect a file or keep the existing path
            'application_files.*.ApplicationID' => 'nullable|string|max:255', // Validate ApplicationID
        ]);
    
        $applicationID = $validatedDataArray['ApplicationID'] ?? $id;
    
        // Retrieve all existing files related to the ApplicationID and delete them
        $existingFiles = ApplicationFile::where('ApplicationID', $applicationID)->get();
    
        foreach ($existingFiles as $existingFile) {
            // Delete the file from storage if it exists
            if (Storage::disk('public')->exists('uploads/' . $existingFile->FilePath)) {
                Storage::disk('public')->delete('uploads/' . $existingFile->FilePath);
            }
            // Delete the file record from the database
            $existingFile->delete();
        }
    
        // Check if new files are being uploaded
        if (isset($validatedDataArray['application_files']) && count($validatedDataArray['application_files']) > 0) {
            foreach ($validatedDataArray['application_files'] as $index => $fileData) {
                $fileToSave = [
                    'ApplicationID' => $fileData['ApplicationID'] ?? $applicationID,
                    'DocumentType' => $fileData['DocumentType'] ?? '',
                    'DocumentName' => $fileData['DocumentName'] ?? null,
                ];
    
                // Check if a new file is being uploaded
                if ($request->hasFile("application_files.{$index}.FilePath")) {
                    $file = $request->file("application_files.{$index}.FilePath");
                    $originalFileName = $file->getClientOriginalName();
    
                    // Store the file in the 'uploads' directory and save the **original** file name
                    $file->storeAs('uploads', $originalFileName, 'public');
    
                    // Store only the file name in the database
                    $fileToSave['FilePath'] = $originalFileName;
                } else {
                    // Keep the existing file path if no new file is uploaded
                    $fileToSave['FilePath'] = $fileData['FilePath'];
                }
    
                // Create a new file record in the database
                ApplicationFile::create($fileToSave);
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
