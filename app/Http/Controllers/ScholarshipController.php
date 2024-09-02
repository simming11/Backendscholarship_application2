<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;

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
        ]);

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
        ]);

        $scholarship = Scholarship::findOrFail($id);
        $scholarship->update($validatedData);

        return response()->json($scholarship);
    }

    // Remove the specified scholarship from the database
    public function destroy($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        $scholarship->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
