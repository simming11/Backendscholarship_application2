<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScholarshipController extends Controller
{
    // Display a listing of scholarships
    public function index()
    {
        // Eager load related models with specific fields
        $scholarships = Scholarship::with([
            'creator:AcademicID', // Load only the AcademicID from the creator
            'type:TypeID,TypeName', // Load only TypeID and TypeName from the type
            'qualifications:ScholarshipID,QualificationID,QualificationText,IsActive', // Load relevant fields from qualifications, including IsActive
            'documents:ScholarshipID,DocumentID,DocumentText,IsActive', // Load relevant fields from documents, including IsActive
            'courses:ScholarshipID,CourseID,CourseName', // Load relevant fields from courses
            'files:ScholarshipID,FileID,FilePath,FileType', // Load relevant fields from files
            'images:ScholarshipID,ImageID,ImagePath' // Load relevant fields from images
        ])->get();
    
        return response()->json($scholarships);
    }
    
    // Store a newly created scholarship in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ScholarshipName' => 'required|string|max:255',
            'Year' => 'required|integer',
            'Num_scholarship' => 'required|integer',
            'Minimum_GPA' => 'required|numeric|between:0,4.0',
            'YearLevel' => 'nullable|string|max:255',
            'TypeID' => 'required|integer|exists:scholarship_types,TypeID',
            'StartDate' => 'required|date',
            'EndDate' => 'required|date',
            'CreatedBy' => 'required|integer|exists:academics,AcademicID',
            'AnnouncementFile' => 'nullable|file|mimes:pdf,doc,docx', // Validate AnnouncementFile if present
        ]);

        // Handle file upload for AnnouncementFile
        if ($request->hasFile('AnnouncementFile')) {
            $file = $request->file('AnnouncementFile');
            $originalFileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/announcements', $originalFileName, 'public');
            $validatedData['AnnouncementFile'] = $filePath; // Save the file path to the database
        }

        $scholarship = Scholarship::create($validatedData);

        return response()->json($scholarship, 201); // 201 Created
    }

    // Display the specified scholarship
    public function show($id)
    {
        $scholarship = Scholarship::with([
            'creator:AcademicID', // Load only the AcademicID from the creator
            'type:TypeID,TypeName', // Load only TypeID and TypeName from the type
            'qualifications:ScholarshipID,QualificationID,QualificationText,IsActive', // Load relevant fields from qualifications, including IsActive
            'documents:ScholarshipID,DocumentID,DocumentText,IsActive', // Load relevant fields from documents, including IsActive
            'courses:ScholarshipID,CourseID,CourseName', // Load relevant fields from courses
            'files:ScholarshipID,FileID,FilePath,FileType', // Load relevant fields from files
            'images:ScholarshipID,ImageID,ImagePath' // Load relevant fields from images
        ])->findOrFail($id);

        return response()->json($scholarship);
    }

    // Update the specified scholarship in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'ScholarshipName' => 'sometimes|required|string|max:255',
            'Year' => 'sometimes|required|integer',
            'Num_scholarship' => 'sometimes|required|integer',
            'Minimum_GPA' => 'sometimes|required|numeric|between:0,4.0',
            'YearLevel' => 'nullable|string|max:255',
            'TypeID' => 'sometimes|required|integer|exists:scholarship_types,TypeID',
            'StartDate' => 'sometimes|required|date',
            'EndDate' => 'sometimes|required|date',
            'CreatedBy' => 'sometimes|required|integer|exists:academics,AcademicID',
            'AnnouncementFile' => 'nullable|file|mimes:pdf,doc,docx', // Validate AnnouncementFile if present
        ]);

        $scholarship = Scholarship::findOrFail($id);

        // Handle file upload for AnnouncementFile
        if ($request->hasFile('AnnouncementFile')) {
            $file = $request->file('AnnouncementFile');
            $originalFileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/announcements', $originalFileName, 'public');
            $validatedData['AnnouncementFile'] = $filePath; // Save the file path to the database
        }

        $scholarship->update($validatedData);

        return response()->json($scholarship);
    }

    public function updateAnnouncementFile(Request $request, $id)
    {
        // Validate the AnnouncementFile
        $validatedData = $request->validate([
            'AnnouncementFile' => 'required|file|mimes:pdf,doc,docx|max:2048', // Set file types and size restrictions
        ]);
    
        // Find the scholarship by its ID
        $scholarship = Scholarship::findOrFail($id);
    
        // Delete the previous file if it exists
        if ($scholarship->AnnouncementFile && Storage::disk('public')->exists($scholarship->AnnouncementFile)) {
            Storage::disk('public')->delete($scholarship->AnnouncementFile); // Delete the previous file
        }
    
        // Handle file upload for AnnouncementFile
        if ($request->hasFile('AnnouncementFile')) {
            $file = $request->file('AnnouncementFile');
            $originalFileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/announcements', $originalFileName, 'public');
            
            // Update the 'AnnouncementFile' field in the scholarship
            $scholarship->update([
                'AnnouncementFile' => $filePath,
            ]);
    
            // Return the updated scholarship data with download link
            return response()->json([
                'message' => 'Announcement file updated successfully.',
                'file_path' => Storage::url($filePath), // Return the public URL for downloading
                'scholarship' => $scholarship,
            ]);
        }
    
        // Return an error if no file was uploaded
        return response()->json([
            'message' => 'No file provided for AnnouncementFile.',
        ], 400);
    }

    public function downloadAnnouncementFile($id)
    {
        // Find the scholarship by its ID
        $scholarship = Scholarship::findOrFail($id);
    
        // Check if the scholarship has an announcement file
        if (!$scholarship->AnnouncementFile || !Storage::disk('public')->exists($scholarship->AnnouncementFile)) {
            return response()->json(['error' => 'File not found.'], 404);
        }
    
        // Get the file path and original file name
        $filePath = Storage::disk('public')->path($scholarship->AnnouncementFile);
        $originalFileName = basename($scholarship->AnnouncementFile);
    
        // Return the file for download with appropriate headers
        return response()->download($filePath, $originalFileName, [
            'Content-Disposition' => 'attachment; filename="' . $originalFileName . '"',
        ]);
    }

    // Remove the specified scholarship from the database
    public function destroy($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        $scholarship->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
