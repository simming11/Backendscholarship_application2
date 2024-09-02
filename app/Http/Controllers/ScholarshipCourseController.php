<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipCourse;
use Illuminate\Http\Request;

class ScholarshipCourseController extends Controller
{
    // Display a listing of scholarship courses with pagination
    public function index()
    {
        $courses = ScholarshipCourse::paginate(10); // Example with pagination
        return response()->json($courses);
    }

    // Store newly created scholarship courses in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ScholarshipID' => 'required|integer|exists:scholarships,ScholarshipID',
            'CourseName' => 'required|array', // Validate that CourseName is an array
            'CourseName.*' => 'required|string|max:255',
        ]);

        $courses = [];
        foreach ($validatedData['CourseName'] as $courseName) {
            // Check if a course with the same name and ScholarshipID already exists
            $existingCourse = ScholarshipCourse::where('CourseName', $courseName)
                                               ->where('ScholarshipID', $validatedData['ScholarshipID'])
                                               ->first();

            if ($existingCourse) {
                // Delete the existing course
                $existingCourse->delete();
            }

            // Create the new course
            $courses[] = ScholarshipCourse::create([
                'ScholarshipID' => $validatedData['ScholarshipID'],
                'CourseName' => $courseName,
            ]);
        }

        return response()->json($courses, 201); // 201 Created
    }

    // Display the specified scholarship course
    public function show($id)
    {
        $course = ScholarshipCourse::findOrFail($id);
        return response()->json($course);
    }

    // Update the specified scholarship course in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'ScholarshipID' => 'sometimes|required|integer|exists:scholarships,ScholarshipID',
            'CourseName' => 'required|array', // Validate that CourseName is an array
            'CourseName.*' => 'required|string|max:255', // Validate each item in the array
        ]);
    
        // Remove all existing courses for the given ScholarshipID
        ScholarshipCourse::where('ScholarshipID', $validatedData['ScholarshipID'])->delete();
    
        $courses = [];
        foreach ($validatedData['CourseName'] as $courseName) {
            $courses[] = ScholarshipCourse::create([
                'ScholarshipID' => $validatedData['ScholarshipID'],
                'CourseName' => $courseName,
            ]);
        }
    
        return response()->json($courses, 200); // Return the updated courses
    }
    

    // Remove the specified scholarship course from the database
    public function destroy($id)
    {
        $course = ScholarshipCourse::findOrFail($id);
        $course->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
